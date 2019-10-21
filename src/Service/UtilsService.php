<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Extra\Contract\UtilsServiceInterface;
use Ramsey\Uuid\Uuid;

final class UtilsService implements UtilsServiceInterface
{
    /**
     * @return Uuid|\Ramsey\Uuid\UuidInterface
     * @throws \Exception
     */
    public function uuid()
    {
        return Uuid::uuid4();
    }
}