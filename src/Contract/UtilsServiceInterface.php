<?php
declare(strict_types=1);

namespace Hyperf\Extra\Contract;

use Exception;
use Hyperf\HttpMessage\Cookie\Cookie;
use Ramsey\Uuid\UuidInterface;

interface UtilsServiceInterface
{
    /**
     * @return UuidInterface
     * @throws Exception
     */
    public function uuid(): UuidInterface;

    /**
     * Create Cookie Object
     * @param string $name
     * @param string $value
     * @param array $options
     * @return Cookie
     */
    public function cookie(string $name, string $value, array $options = []): Cookie;
}