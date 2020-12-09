<?php
declare(strict_types=1);

namespace Hyperf\Extra\Common;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;

/**
 * Class RedisModel
 * @package Hyperf\Extra\Common
 */
abstract class RedisModel
{
    protected string $key;
    protected ContainerInterface $container;
    /**
     * @var Redis|\Redis
     */
    protected Redis $redis;

    /**
     * RedisModel constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->redis = $container->get(\Redis::class);
    }

    /**
     * 获取键名
     * @param string $name
     * @return string
     */
    protected function getKey(string $name = ''): string
    {
        $config = $this->container->get(ConfigInterface::class);
        return $config->get('app_name') . ':' . $this->key . $name;
    }
}
