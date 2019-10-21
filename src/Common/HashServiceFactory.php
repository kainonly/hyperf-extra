<?php

namespace Hyperf\Extra\Common;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Service\HashService;
use Psr\Container\ContainerInterface;

class HashServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $params = $config->get('hashing');
        $driver = $params['driver'];
        $options = !empty($params[$driver]) ? $params[$driver] : [];
        return make(HashService::class, [
            $driver,
            $options
        ]);
    }
}