<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Extra\Common\CorsFactory;
use Psr\Container\ContainerInterface;

class CorsService
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $options = $config->get('cors');
        return make(CorsFactory::class, [
            $options
        ]);
    }
}