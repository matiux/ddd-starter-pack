<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain;

use DDDStarterPack\Aggregate\Domain\BasicEntityId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BasicEntityIdTest extends TestCase
{
    /**
     * @test
     */
    public function create_randomic_uuid(): void
    {
        $myId = BasicEntityId::create();

        $entity = new class($myId) {
            private BasicEntityId $id;

            public function __construct(BasicEntityId $id)
            {
                $this->id = $id;
            }

            public function id(): BasicEntityId
            {
                return $this->id;
            }
        };

        self::assertInstanceOf(BasicEntityId::class, $entity->id());
        self::assertMatchesRegularExpression(BasicEntityId::UUID_PATTERN, (string) $entity->id());
    }

    /**
     * @test
     */
    public function create_specific_uuid(): void
    {
        $myId = BasicEntityId::createFrom('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5');

        self::assertEquals('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5', $myId->id());
        self::assertIsString($myId->id());
    }

    /**
     * @test
     */
    public function create_numeric_id(): void
    {
        $myId = BasicEntityId::createFrom(5);

        self::assertEquals(5, $myId->id());
        self::assertIsInt($myId->id());
    }

    /**
     * @test
     */
    public function create_null_id(): void
    {
        $myId = BasicEntityId::createNull();

        self::assertTrue($myId->isNull());
        self::assertNull($myId->id());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_input_id_is_invalid(): void
    {
        self::expectException(InvalidArgumentException::class);

        BasicEntityId::createFrom('');
    }

    /**
     * @test
     */
    public function is_should_verify_if_id_is_in_uuid_v4_format(): void
    {
        self::assertTrue(BasicEntityId::isValidUuid('79fe4c6b-87f6-4093-98f9-ce6a193ab2a5'));
        self::assertFalse(BasicEntityId::isValidUuid('12345'));
    }
}
