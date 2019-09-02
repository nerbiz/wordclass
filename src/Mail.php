<?php

namespace Nerbiz\Wordclass;

use Exception;

class Mail
{
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
                'smtp_host' => ['type' => 'text', 'title' => __('Host', 'wordclass')],
                'smtp_port' => ['type' => 'text', 'title' => __('Port', 'wordclass')],
                'smtp_encryption' => ['type' => 'text', 'title' => __('Encryption', 'wordclass')],
                'smtp_username' => ['type' => 'text', 'title' => __('Username', 'wordclass')],
                'smtp_password' => ['type' => 'password', 'title' => __('Password', 'wordclass')],
            ])
            ->create();

        $passwordField = (new Init())->getPrefix() . '_smtp_password';
        $crypto = new Crypto($encryptionKey ?? SECURE_AUTH_KEY);

        // Encrypt the SMTP password before storing
        add_filter('pre_update_option_' . $passwordField, function ($newValue, $oldValue) use ($crypto) {
            return $crypto->encrypt($newValue);
        }, 10, 2);

        // Decrypt the SMTP password before using
        add_filter('option_' . $passwordField, function ($value, $optionName) use ($crypto) {
            return $crypto->decrypt($value);
        }, 10, 2);

        return $this;
    }
}
