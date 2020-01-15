<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Hyperf\Extra\Contract\HashInterface;
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

    public function setUp()
    {
        parent::setUp();
        $this->password = 'mypassword';
    }

    public function testCreateHash()
    {
        $hashContext = $this->hash->create($this->password);
        $this->assertNotEmpty($hashContext);
        return $hashContext;
    }

    /**
     * @depends testCreateHash
     */
    public function testCheckHash(string $context)
    {
        $this->assertTrue($this->hash->check($this->password, $context));
    }
}