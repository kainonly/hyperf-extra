<?php

namespace Hyperf\Extra\Support\Facades;

use Hyperf\Extra\Common\Facade;
use Hyperf\Extra\Contract\UtilsServiceInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class Utils
 * @package Hyperf\Extra\Support\Facades
 * @method static Uuid|\Ramsey\Uuid\UuidInterface uuid()
 */
class Utils extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UtilsServiceInterface::class;
    }
}