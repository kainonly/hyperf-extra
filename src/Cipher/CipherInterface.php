<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cipher;

interface CipherInterface
{
    /**
     * encrypt
     * @param string|array $context Encrypted content
     * @return string Ciphertext
     */
    public function encrypt($context): string;

    /**
     * decrypt
     * @param string $ciphertext Ciphertext
     * @param bool $auto_conver
     * @return string|array Conversion data
     */
    public function decrypt(string $ciphertext, bool $auto_conver = true);
}