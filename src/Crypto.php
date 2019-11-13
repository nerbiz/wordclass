<?php

namespace Nerbiz\Wordclass;

use Exception;

class Crypto
{
    /**
     * The encryption key
     * @var string
     */
    protected $encryptionKey;

    /**
     * The cipher to use for encrypting and decrypting
     * @var string
     */
    protected $cipher = 'aes-256-cbc';

    /**
     * @param string $encryptionKey
     */
    public function __construct(string $encryptionKey = SECURE_AUTH_KEY)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Encrypt a plaintext value
     * @param string $original
     * @return string
     */
    public function encrypt(string $original): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $encrypted = openssl_encrypt($original, $this->cipher, $this->encryptionKey, 0, $iv);

        // Include the IV, which is needed for decrypting
        return $encrypted . ':' . base64_encode($iv);
    }

    /**
     * Decrypt an encrypted value
     * @param string $encrypted
     * @return string
     */
    public function decrypt(string $encrypted): string
    {
        // Extract the encrypted value and the used IV
        list($encrypted, $iv) = explode(':', $encrypted, 2);
        $iv = base64_decode($iv);

        return openssl_decrypt($encrypted, $this->cipher, $this->encryptionKey, 0, $iv);
    }
}
