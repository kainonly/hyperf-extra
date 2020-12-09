<?php
declare(strict_types=1);

namespace Hyperf\Extra\Utils;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class UtilsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $cookie = $config->get('cookie');
        return make(Utils::class, [
            $cookie
        ]);
    }
}