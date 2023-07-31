<?php

namespace Nerbiz\WordClass;

class Encryption
{
    /**
     * @param string $key
     * @param string $cipher The cipher to use for encrypting and decrypting
     */
    public function __construct(
        protected string $key,
        protected string $cipher = 'aes-256-cbc'
    ) {}

    /**
     * Encrypt a plaintext value
     * @param string $original
     * @return string
     */
    public function encrypt(string $original): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $encrypted = openssl_encrypt($original, $this->cipher, $this->key, 0, $iv);

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
        [$encrypted, $iv] = explode(':', $encrypted, 2);
        $iv = base64_decode($iv);

        return openssl_decrypt($encrypted, $this->cipher, $this->key, 0, $iv);
    }
}
