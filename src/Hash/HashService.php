<?php
declare(strict_types=1);

namespace Hyperf\Extra\Hash;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class HashService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $options = $config->get('hashing');
        return make(HashFactory::class, [
            $options
        ]);
    }
}