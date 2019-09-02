<?php

namespace Nerbiz\Wordclass;

use Exception;
use PHPMailer;
use phpmailerException;
use SMTP;

class Mail
{
    public function __construct()
    {
        // Include the PHPMailer classfiles
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
    }

    /**
     * @param string|null $encryptionKey The key to encrypt/decrypt the SMTP password with
     * @return self
     * @throws Exception
     */
    public function addSmtpSettingsPage(?string $encryptionKey = null): self
    {
        // Create the settings page
        $settingsPage = new SettingsPage();
        $settingsPage->setParentSlug('options-general.php')
            ->setPageTitle(__('SMTP settings', 'wordclass'))
            ->addSection('smtp_values', __('SMTP values', 'wordclass'), null, [
                'smtp_enable' => ['type' => 'checkbox', 'title' => __('Enable SMTP?', 'wordclass')],
                'smtp_host' => ['type' => 'text', 'title' => __('Host', 'wordclass')],
                'smtp_port' => ['type' => 'text', 'title' => __('Port', 'wordclass')],
                'smtp_encryption' => ['type' => 'text', 'title' => __('Encryption', 'wordclass')],
                'smtp_username' => ['type' => 'text', 'title' => __('Username', 'wordclass')],
                'smtp_password' => [
                    'type' => 'password',
                    'title' => __('Password', 'wordclass'),
                    'description' => __('The password is stored encrypted', 'wordclass'),
                ],
            ])
            ->addSection('smtp_test', __('Test settings', 'wordclass'), null, [
                'smtp_test_recipient' => ['type' => 'text', 'title' => __('Recipient', 'wordclass')],
                'smtp_test_subject' => ['type' => 'text', 'title' => __('Subject', 'wordclass')],
                'smtp_test_content' => ['type' => 'textarea', 'title' => __('Content', 'wordclass')],
                'smtp_test_enable' => [
                    'type' => 'checkbox',
                    'title' => __('Send testmail?', 'wordclass'),
                    'description' => __('If checked, a testmail will be sent when saving these settings', 'wordclass'),
                ],
            ])
            ->create();

        $crypto = new Crypto($encryptionKey ?? SECURE_AUTH_KEY);
        $passwordField = (new Init())->getPrefix() . '_smtp_password';
        $enableTestField = (new Init())->getPrefix() . '_smtp_test_enable';

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
     * Send a testmail, using the filled in values
     */
    protected function sendTestMail(): void
    {
        $phpMailer = new PHPMailer(true);
        $helpers = new Helpers();

        $this->applySmtpToPhpMailer($phpMailer);
        $phpMailer->SMTPDebug = SMTP::DEBUG_CLIENT;
        $phpMailer->CharSet = get_bloginfo('charset');
        $phpMailer->Timeout = 10;
        $phpMailer->ContentType = 'text/plain';
        $phpMailer->isHTML(false);
        $phpMailer->SMTPAutoTLS = false;
        $phpMailer->setFrom(get_option('admin_email'), get_bloginfo('name'));
        $phpMailer->addAddress($helpers->getOption('smtp_test_recipient'));
        $phpMailer->Subject = $helpers->getOption('smtp_test_subject');
        $phpMailer->Body = $helpers->getOption('smtp_test_content');

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
            add_action('admin_notices', function () {
                echo sprintf(
                    '<div class="notice notice-error is-dismissible"><p>%s<br>%s</p></div>',
                    __('An error occured when trying to send the testmail:', 'wordpress'),
                    $phpMailer->ErrorInfo
                );
            });
        }
    }

    /**
     * Apply the SMTP settings to all outgoing WP mail
     * @return self
     */
    public function enableSmtp(): self
    {
        add_action('phpmailer_init', function (PHPMailer $phpMailer) {
            return $this->applySmtpToPhpMailer($phpMailer);
        });

        return $this;
    }

    /**
     * @param PHPMailer $phpMailer
     * @return PHPMailer
     */
    protected function applySmtpToPhpMailer(PHPMailer $phpMailer): PHPMailer
    {
        $helpers = new Helpers();

        if ($helpers->getOption('smtp_enable') === null) {
            return $phpMailer;
        }

        $phpMailer->isSMTP();
        $phpMailer->Host = $helpers->getOption('smtp_host');
        $phpMailer->Port = $helpers->getOption('smtp_port');
        $phpMailer->SMTPSecure = $helpers->getOption('smtp_encryption');

        $username = $helpers->getOption('smtp_username');
        $password = $helpers->getOption('smtp_password');
        if ($username !== null || $password !== null) {
            $phpMailer->SMTPAuth = true;
            $phpMailer->Username = $username;
            $phpMailer->Password = $password;
        }

        return $phpMailer;
    }
}
