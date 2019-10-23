<?php
declare(strict_types=1);

namespace Hyperf\Extra\Contract;

use Hyperf\HttpMessage\Cookie\Cookie;
use Ramsey\Uuid\Uuid;

interface UtilsServiceInterface
{
    /**
     * @return Uuid|\Ramsey\Uuid\UuidInterface
     * @throws \Exception
     */
    public function uuid();

    /**
     * Create Cookie Object
     * @param string $name
     * @param string $value
     * @param array $options
     * @return Cookie
     */
    public function cookie(string $name, string $value, array $options = []);
}