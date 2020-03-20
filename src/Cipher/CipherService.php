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
        $key = $config->get('app_key');
        $iv = $config->get('app_name');
        return make(CipherFactory::class, [
            $key,
            $iv
        ]);
    }
}