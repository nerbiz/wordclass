<?php

namespace Nerbiz\Wordclass;

use Nerbiz\Wordclass\InputFields\CheckboxInputField;
use Nerbiz\Wordclass\InputFields\PasswordInputField;
use Nerbiz\Wordclass\InputFields\TextareaInputField;
use Nerbiz\Wordclass\InputFields\TextInputField;
use PHPMailer\PHPMailer\PHPMailer;
use WP_Error;

class Mail
{
    /**
     * Enable SMTP for all mails, add a settings page
     * @param string|null $encryptionKey The key for encrypting/decrypting the SMTP password
     * @return self
     */
    public function addSmtpSupport(?string $encryptionKey = null): self
    {
        $this->addSmtpSettingsPage();
        $this->addOptionHooks($encryptionKey);
        $this->addSmtpMailHook();

        return $this;
    }

    /**
     * Add the settings page for SMTP settings
     * @return void
     */
    protected function addSmtpSettingsPage(): void
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
                    new PasswordInputField('password', __('Password', 'wordclass'), __('Encryption is used to store the password', 'wordclass')),
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
    }

    /**
     * Add hooks for storing/reading options
     * @param string|null $encryptionKey
     * @return void
     */
    protected function addOptionHooks(?string $encryptionKey = null): void
    {
        $crypto = new Crypto($encryptionKey);
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
    }

    /**
     * Apply the SMTP settings to all outgoing WP mail
     * @return void
     */
    protected function addSmtpMailHook(): void
    {
        add_action('phpmailer_init', function (PHPMailer $phpMailer) {
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
        });
    }

    /**
     * Send a testmail, using the filled in values
     * @return void
     */
    protected function sendTestMail(): void
    {
        add_action('wp_mail_failed', function(WP_Error $error) {
            // Add an admin error notice
            add_action('admin_notices', function () use ($error) {
                echo sprintf(
                    '<div class="notice notice-error is-dismissible"><p>%s<br>%s</p></div>',
                    __('An error occured when trying to send the testmail:', 'wordpress'),
                    $error->get_error_message()
                );
            });
        });

        // (Try to) send the email
        $options = new Options();
        $headers = [
            'Content-Type: text/html; charset=' . get_bloginfo('charset'),
            'From: ' . sprintf('%s <%s>', get_bloginfo('name'), get_option('admin_email')),
        ];

        $mailIsSent = wp_mail(
            $options->get('smtp_test_recipient'),
            $options->get('smtp_test_subject'),
            nl2br($options->get('smtp_test_content')),
            $headers
        );

        // Mail is sent successfully
        if ($mailIsSent) {
            // Add an admin success notice
            add_action('admin_notices', function () {
                echo sprintf(
                    '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
                    __('The testmail was sent successfully.', 'wordpress')
                );
            });
        }
    }
}
