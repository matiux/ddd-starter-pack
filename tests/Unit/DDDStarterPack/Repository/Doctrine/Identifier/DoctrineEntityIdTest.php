<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Repository\Doctrine\Identifier;

use DDDStarterPack\Repository\Doctrine\Identifier\DoctrineUuidEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\PersonId;

class DoctrineEntityIdTest extends TestCase
{
    /**
     * @test
     */
    public function php_value_should_be_same_of_input_if_database_value_is_not_valid_uuid_or_custom_valid(): void
    {
        MyIdUuid::addType('MyId', MyIdUuid::class);
        $id = MyIdUuid::getType('MyId');

        $phpValue = $id->convertToPHPValue('foo_id', \Mockery::mock(AbstractPlatform::class));

        self::assertSame('foo_id', $phpValue);
    }
}

class MyIdUuid extends DoctrineUuidEntityId
{
    protected function getFQCN(): string
    {
        return PersonId::class;
    }
}
