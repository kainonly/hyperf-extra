<?php

namespace Hyperf\Extra\Contract;

use Ramsey\Uuid\Uuid;

interface UtilsServiceInterface
{
    /**
     * @return Uuid|\Ramsey\Uuid\UuidInterface
     * @throws \Exception
     */
    public function uuid();
}