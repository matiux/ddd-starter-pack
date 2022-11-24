<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Identifier;

use DDDStarterPack\Aggregate\Domain\Identifier\EntityId;
use DDDStarterPack\Aggregate\Domain\Identifier\UuidEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineUuidEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UuidEntityId ? $value->value() : $value ?? null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $value = $this->prepareValue($value);

        if (
            is_null($value)
            || (!$this->isValidUuid($value) && !$this->isCustomValid())
        ) {
            return $value;
        }

        $className = $this->getFQCN();

        return $className::createFrom($value);
    }

    /**
     * @param mixed $value
     *
     * @return null|string
     */
    private function prepareValue(mixed $value): null|string
    {
        return match (true) {
            is_object($value), is_null($value) => null,
            default => (string) $value
        };
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function isValidUuid(string $value): bool
    {
        return UuidEntityId::isValidUuid($value);
    }

    protected function isCustomValid(): bool
    {
        return false;
    }

    /**
     * @return class-string<EntityId>
     */
    abstract protected function getFQCN(): string;
}
