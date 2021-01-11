<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class HelperTest extends TestCase
{
    public function testUuid(): void
    {
        try {
            $uuid = uuid();
            self::assertTrue(Uuid::isValid($uuid->toString()));
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }

    public function testStringy(): void
    {
        $stringy = \stringy('hello');
        self::assertEquals('e', $stringy->at(1));
    }
}