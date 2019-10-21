<?php


namespace Hyperf\Extra\Support\Facades;

use Hyperf\Extra\Common\Facade;
use Hyperf\Extra\Contract\HashServiceInterface;

/**
 * Class Hash
 * @package Hyperf\Extra\Support\Facades
 * @method static string make(string $password, array $options = [])
 * @method static boolean check(string $password, string $hash)
 */
class Hash extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HashServiceInterface::class;
    }
}