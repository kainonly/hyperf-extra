<?php
declare(strict_types=1);

namespace Hyperf\Extra\Common;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Service\UtilsService;
use Psr\Container\ContainerInterface;

class UtilsServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $cookie = $config->get('cookie');
        return make(UtilsService::class, [
            $cookie,
        ]);
    }
}