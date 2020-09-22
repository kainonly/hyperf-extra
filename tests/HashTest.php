<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Hyperf\Extra\Hash\HashInterface;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    private HashInterface $hash;
    private string $password;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $container = ApplicationContext::getContainer();
        $this->hash = $container->get(HashInterface::class);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->password = 'mypassword';
    }

    public function testCreateHash()
    {
        $hashContext = $this->hash->create($this->password);
        self::assertNotEmpty($hashContext);
        return $hashContext;
    }

    /**
     * @depends testCreateHash
     * @param string $context
     */
    public function testCheckHash(string $context): void
    {
        self::assertTrue($this->hash->check($this->password, $context));
    }
}