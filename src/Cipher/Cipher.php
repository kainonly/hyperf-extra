<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cipher;

use phpseclib3\Crypt\ChaCha20;
use phpseclib3\Crypt\Common\SymmetricKey;

class Cipher implements CipherInterface
{
    /**
     * @var string 密钥
     */
    private string $key;

    /**
     * @var string 参考量
     */
    private string $mixed;

    /**
     * CipherService constructor.
     * @param string $key
     * @param string $mixed
     */
    public function __construct(string $key, string $mixed)
    {
        $this->key = $key;
        $this->mixed = $mixed;
    }

    /**
     * 生产加密工具
     * @return SymmetricKey
     */
    private function factoryCipher(): SymmetricKey
    {
        $cipher = new ChaCha20();
        $cipher->setKey($this->key);
        $nonce = str_pad(substr($this->mixed, 0, 8), 8, "\0");
        $cipher->setNonce($nonce);
        return $cipher;
    }

    /**
     * @param array|string $context
     * @return string
     * @inheritDoc
     */
    public function encrypt($context): string
    {
        $cipher = $this->factoryCipher();
        if (is_string($context)) {
            return base64_encode($cipher->encrypt($context));
        }
        if (is_array($context)) {
            return base64_encode($cipher->encrypt(json_encode($context)));
        }
        return '';
    }

    /**
     * @param string $ciphertext
     * @param bool $auto_conver
     * @return array|mixed|string
     * @inheritDoc
     */
    public function decrypt(string $ciphertext, bool $auto_conver = true)
    {
        $cipher = $this->factoryCipher();
        $data = $cipher->decrypt(base64_decode($ciphertext));
        return stringy($data)->isJson() && $auto_conver ? json_decode($data, true) : $data;
    }
}