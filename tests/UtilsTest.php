<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Exception;
use Hyperf\Extra\Utils\UtilsInterface;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UtilsTest extends TestCase
{
    private UtilsInterface $utils;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $container = ApplicationContext::getContainer();
        $this->utils = $container->get(UtilsInterface::class);
    }

    /**
     * @throws Exception
     * @expectedException
     */
    public function testUuid()
    {
        $uuid = $this->utils->uuid();
        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertNotEmpty($uuid->toString());
    }
}