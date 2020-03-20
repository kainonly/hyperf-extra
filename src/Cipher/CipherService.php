<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cipher;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;

class CipherService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $options = $config->get('hashing');
        return make(CipherFactory::class, [
            $options
        ]);
    }
}