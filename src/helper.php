<?php
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use Hyperf\Utils\Str;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringy\Stringy;

if (!function_exists('AutoController')) {
    /**
     * 控制器路由绑定
     * @param string $controller
     * @param array $options
     * @throws ReflectionException
     */
    function AutoController(string $controller, array $options = [])
    {
        $reflect = new ReflectionClass($controller);
        $path = Str::snake(Str::before($reflect->getShortName(), 'Controller'));
        $methods = array_filter(
            $reflect->getMethods(ReflectionMethod::IS_PUBLIC),
            static fn($v) => !in_array($v->name, config('curd.auto.ignore'), true)
        );
        $middlewares = $options['middleware'] ?? [];
        foreach ($methods as $method) {
            $middleware = [];
            foreach ($middlewares as $key => $value) {
                if (is_string($value)) {
                    $middleware[] = $value;
                }
                if (is_array($value) && in_array($method->name, $value, true)) {
                    $middleware[] = $key;
                }
            }
            Router::addRoute(
                ['POST', 'OPTIONS'],
                '/' . $path . '/' . $method->name,
                [$controller, $method->name],
                [
                    'middleware' => $middleware
                ]
            );
        }
    }
}

if (!function_exists('uuid')) {
    /**
     * 生成 UUID v4
     * @return UuidInterface
     * @throws Exception
     */
    function uuid(): UuidInterface
    {
        return Uuid::uuid4();
    }
}

if (!function_exists('stringy')) {
    /**
     * 创建 Stringy
     * @param string $str
     * @param string $encoding
     * @return Stringy
     */
    function stringy(string $str, string $encoding = ''): Stringy
    {
        return Stringy::create($str, $encoding);
    }
}