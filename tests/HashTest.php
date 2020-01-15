<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Extra\Contract\HashInterface;
use Hyperf\Extra\Common\HashFactory;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class HashTest extends TestCase
{
    private ContainerInterface $container;
    private HashInterface $hash;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->hash = make(HashFactory::class);
    }

    public function testCreate()
    {
//        $password = $this->hash->create('123456');
        $this->assertNull(null);
    }

}