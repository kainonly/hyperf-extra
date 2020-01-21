<?php
declare(strict_types=1);

namespace Hyperf\Extra\Utils;

use Hyperf\HttpMessage\Cookie\Cookie;

interface UtilsInterface
{
    /**
     * Create Cookie Object
     * @param string $name
     * @param string $value
     * @param array $options
     * @return Cookie
     */
    public function cookie(string $name, string $value, array $options = []): Cookie;
}