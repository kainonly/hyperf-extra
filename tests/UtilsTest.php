<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UtilsTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUuid(): void
    {
        $uuid = uuid();
        self::assertInstanceOf(Uuid::class, $uuid);
        self::assertNotEmpty($uuid->toString());
    }
}