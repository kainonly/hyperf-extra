<?php
declare(strict_types=1);

namespace Hyperf\Extra\Token;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class TokenService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $key = $config->get('app_key');
        $options = $config->get('token');
        return make(TokenFactory::class, [
            $key,
            $options
        ]);
    }
}