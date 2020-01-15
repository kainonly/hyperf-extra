<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Common\TokenFactory;
use Psr\Container\ContainerInterface;

class TokenService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $params = $config->get('token');
        return make(TokenFactory::class, [
            $params['key'],
            $params['options']
        ]);
    }
}