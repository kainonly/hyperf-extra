<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cipher;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;

class CipherFactory
{
    public function __invoke(ContainerInterface $container): Cipher
    {
        $config = $container->get(ConfigInterface::class);
        $key = $config->get('app_key');
        $mixed = $config->get('app_name');
        return make(Cipher::class, [
            $key,
            $mixed
        ]);
    }
}