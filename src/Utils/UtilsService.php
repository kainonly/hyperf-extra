<?php
declare(strict_types=1);

namespace Hyperf\Extra\Utils;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class UtilsService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $cookie = $config->get('cookie');
        return make(UtilsFactory::class, [
            $cookie
        ]);
    }
}