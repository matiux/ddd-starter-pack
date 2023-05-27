<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Identity;

use DDDStarterPack\Identity\UuidV4;
use PHPUnit\Framework\TestCase;

class UuidV4Test extends TestCase
{
    /**
     * @test
     */
    public function create_randomic_uuid(): void
    {
        self::assertMatchesRegularExpression(UuidV4::UUID_PATTERN, (string) UuidV4::new());
    }

    /**
     * @test
     */
    public function create_specific_uuid(): void
    {
        $myId = UuidV4::from('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5');

        self::assertEquals('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5', $myId->value());
        self::assertIsString($myId->value());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_input_id_is_invalid(): void
    {
        self::expectException(\InvalidArgumentException::class);

        UuidV4::from('');
    }

    /**
     * @test
     */
    public function is_should_verify_if_id_is_in_uuid_v4_format(): void
    {
        self::assertTrue(UuidV4::isValidUuid('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5'));
        self::assertFalse(UuidV4::isValidUuid('12345'));
    }
}
