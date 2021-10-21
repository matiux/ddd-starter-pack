<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine;

use DDDStarterPack\Aggregate\Domain\BasicEntityId;
use DDDStarterPack\Aggregate\Domain\EntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof EntityId ? $value->id() : $value ?? null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
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
     * @return null|int|string
     */
    private function prepareValue(mixed $value): null|int|string
    {
        return match (true) {
            is_object($value), is_null($value) => null,
            is_int($value) => $value,
            default => (string) $value
        };
    }

    /**
     * @param int|string $value
     *
     * @return bool
     */
    private function isValidUuid(int|string $value): bool
    {
        return is_string($value) && BasicEntityId::isValidUuid($value);
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
