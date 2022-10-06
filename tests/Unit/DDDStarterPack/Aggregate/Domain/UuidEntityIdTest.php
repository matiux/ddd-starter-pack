<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain;

use DDDStarterPack\Aggregate\Domain\UuidV4EntityId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UuidEntityIdTest extends TestCase
{
    /**
     * @test
     */
    public function create_randomic_uuid(): void
    {
        self::assertMatchesRegularExpression(UuidV4EntityId::UUID_PATTERN, (string) UuidV4EntityId::create());
    }

    /**
     * @test
     */
    public function create_specific_uuid(): void
    {
        $myId = UuidV4EntityId::createFrom('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5');

        self::assertEquals('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5', $myId->id());
        self::assertIsString($myId->id());
    }

//    /**
//     * @test
//     */
//    public function create_numeric_id(): void
//    {
//        $myId = UuidEntityId::createFrom(5);
//
//        self::assertEquals(5, $myId->id());
//        self::assertIsInt($myId->id());
//    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_input_id_is_invalid(): void
    {
        self::expectException(InvalidArgumentException::class);

        UuidV4EntityId::createFrom('');
    }

    /**
     * @test
     */
    public function is_should_verify_if_id_is_in_uuid_v4_format(): void
    {
        self::assertTrue(UuidV4EntityId::isValidUuid('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5'));
        self::assertFalse(UuidV4EntityId::isValidUuid('12345'));
    }
}
