<?php
declare(strict_types=1);

namespace Hyperf\Extra\Hash;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class HashFactory
{
    public function __invoke(ContainerInterface $container): Hash
    {
        $config = $container->get(ConfigInterface::class);
        $options = $config->get('hashing');
        return make(Hash::class, [
            $options
        ]);
    }
}