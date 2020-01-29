<?php

namespace Nerbiz\Wordclass;

use Exception;
use Nerbiz\Wordclass\InputFields\CheckboxInputField;
use Nerbiz\Wordclass\InputFields\PasswordInputField;
use Nerbiz\Wordclass\InputFields\TextareaInputField;
use Nerbiz\Wordclass\InputFields\TextInputField;
use PHPMailer;
use phpmailerException;
use SMTP;

class Mail
{
    /**
     * The key to encrypt and decrypt the SMTP password with
     * @var string|null
     */
    protected $encryptionKey;

    /**
     * @param string|null $encryptionKey The key to encrypt and decrypt the SMTP password with
     */
    public function __construct(?string $encryptionKey = null)
    {
        // Include the PHPMailer classfiles
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';

        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Add required elements for enabling sending mail with SMTP
     * @return self
     * @throws Exception
     */
    public function addSmtpSupport(): self
    {
        $this->addSmtpSettingsPage();
        $this->addSmtpMailHook();

        return $this;
    }

    /**
     * @return self
     * @throws Exception
     */
    protected function addSmtpSettingsPage(): self
    {
        // Create the settings page
        $settingsPage = new SettingsPage();
        $settingsPage->setParentSlug('options-general.php')
            ->setPageTitle(__('SMTP settings', 'wordclass'))
            ->addSection(
                new SettingsPageSection('smtp', __('SMTP values', 'wordclass'), null, [
                    new CheckboxInputField('enable', __('Enable SMTP?', 'wordclass')),
                    new TextInputField('host', __('Host', 'wordclass')),
                    new TextInputField('port', __('Port', 'wordclass')),
                    new TextInputField('encryption', __('Encryption', 'wordclass')),
                    new TextInputField('username', __('Username', 'wordclass')),
                    new PasswordInputField('password', __('Password', 'wordclass'), __('The password is stored encrypted', 'wordclass')),
                ])
            )
            ->addSection(
                new SettingsPageSection('smtp_test', __('Test settings', 'wordclass'), null, [
                    new TextInputField('recipient', __('Recipient', 'wordclass')),
                    new TextInputField('subject', __('Subject', 'wordclass')),
                    new TextareaInputField('content', __('Content', 'wordclass')),
                    new CheckboxInputField('enable', __('Send testmail?', 'wordclass'), __('If checked, a testmail will be sent when saving these settings', 'wordclass')),
                ])
            )
            ->create();

        if ($this->encryptionKey !== null) {
            $crypto = new Crypto($this->encryptionKey);
        } else {
            $crypto = new Crypto();
        }
        $passwordField = Init::getPrefix() . '_smtp_password';
        $enableTestField = Init::getPrefix() . '_smtp_test_enable';

        // Encrypt the SMTP password before storing
        add_filter('pre_update_option_' . $passwordField, function ($newValue, $oldValue) use ($crypto) {
            return $crypto->encrypt($newValue);
        }, 10, 2);

        // Decrypt the SMTP password before using
        add_filter('option_' . $passwordField, function ($value, $optionName) use ($crypto) {
            return $crypto->decrypt($value);
        }, 10, 2);

        // Send a testmail if requested
        add_filter('pre_update_option_' . $enableTestField, function ($newValue, $oldValue) {
            if ($newValue == 1) {
                $this->sendTestMail();
            }

            // Always reset to unchecked
            return '';
        }, 10, 2);

        return $this;
    }

    /**
     * Apply the SMTP settings to all outgoing WP mail
     * @return self
     */
    protected function addSmtpMailHook(): self
    {
        add_action('phpmailer_init', function (PHPMailer $phpMailer) {
            return $this->applySmtpToPhpMailer($phpMailer);
        });

        return $this;
    }

    /**
     * Send a testmail, using the filled in values
     */
    protected function sendTestMail(): void
    {
        $phpMailer = new PHPMailer(true);
        $options = new Options();

        $this->applySmtpToPhpMailer($phpMailer);
        $phpMailer->SMTPDebug = SMTP::DEBUG_OFF;
        $phpMailer->CharSet = get_bloginfo('charset');
        $phpMailer->Timeout = 10;
        $phpMailer->ContentType = 'text/plain';
        $phpMailer->isHTML(false);
        $phpMailer->SMTPAutoTLS = false;
        $phpMailer->setFrom(get_option('admin_email'), get_bloginfo('name'));
        $phpMailer->addAddress($options->get('smtp_test_recipient'));
        $phpMailer->Subject = $options->get('smtp_test_subject');
        $phpMailer->Body = $options->get('smtp_test_content');

        // Try to send the e-mail
        try {
            $phpMailer->send();

            // Add an admin notice
            add_action('admin_notices', function () {
                echo sprintf(
                    '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
                    __('The testmail was sent successfully.', 'wordpress')
                );
            });
        } catch (phpmailerException $e) {
            // Add an admin notice
            add_action('admin_notices', function () use ($phpMailer) {
                echo sprintf(
                    '<div class="notice notice-error is-dismissible"><p>%s<br>%s</p></div>',
                    __('An error occured when trying to send the testmail:', 'wordpress'),
                    $phpMailer->ErrorInfo
                );
            });
        }
    }

    /**
     * @param PHPMailer $phpMailer
     * @return PHPMailer
     */
    protected function applySmtpToPhpMailer(PHPMailer $phpMailer): PHPMailer
    {
        $options = new Options();

        if ($options->get('smtp_enable') === null) {
            return $phpMailer;
        }

        $phpMailer->isSMTP();
        $phpMailer->Host = $options->get('smtp_host');
        $phpMailer->Port = $options->get('smtp_port');
        $phpMailer->SMTPSecure = $options->get('smtp_encryption');

        $username = $options->get('smtp_username');
        $password = $options->get('smtp_password');
        if ($username !== null || $password !== null) {
            $phpMailer->SMTPAuth = true;
            $phpMailer->Username = $username;
            $phpMailer->Password = $password;
        }

        return $phpMailer;
    }
}
