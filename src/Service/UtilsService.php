<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Common\UtilsFactory;
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