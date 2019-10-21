<?php

namespace Hyperf\Extra\Common;

use Hyperf\Server\Exception\RuntimeException;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;

class Facade
{
    /**
     *
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        return static::$container;
    }

    /**
     * Set the application instance.
     * @param ContainerInterface $container
     * @return void
     */
    public static function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        static::$container = ApplicationContext::getContainer();
        $service = $container->get(static::getFacadeAccessor());

        return call_user_func_array(
            [$service, $method],
            $arguments
        );
    }
}