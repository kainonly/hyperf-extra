<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cors;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class CorsFactory
{
    public function __invoke(ContainerInterface $container): Cors
    {
        $config = $container->get(ConfigInterface::class);
        $options = $config->get('cors');
        return make(Cors::class, [
            $options
        ]);
    }
}