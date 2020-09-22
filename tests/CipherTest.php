<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Hyperf\Extra\Cipher\CipherInterface;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Framework\TestCase;

class CipherTest extends TestCase
{
    private CipherInterface $cipher;
    private array $data = [
        'name' => 'kain'
    ];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $container = ApplicationContext::getContainer();
        $this->cipher = $container->get(CipherInterface::class);
    }

    /**
     * @return string
     */
    public function testEncrypt(): string
    {
        $context = $this->cipher->encrypt($this->data);
        self::assertNotEmpty($context, '加密不成功');
        return $context;
    }

    /**
     * @param string $context
     * @depends testEncrypt
     */
    public function testDecrypt(string $context): void
    {
        $result = $this->cipher->decrypt($context);
        self::assertEquals($this->data, $result, '解密信息不对称');
    }
}