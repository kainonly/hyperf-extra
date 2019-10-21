<?php

namespace Hyperf\Extra\Provider;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Contract\UtilsServiceInterface;
use Hyperf\Extra\Service\TokenService;
use Psr\Container\ContainerInterface;

final class TokenServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $params = $config->get('token');
        return make(TokenService::class, [
            $params['key'],
            $params['options'],
            $container->get(UtilsServiceInterface::class)
        ]);
    }
}