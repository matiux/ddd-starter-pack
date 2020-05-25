<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine;

use DDDStarterPack\Domain\Aggregate\BasicEntityId;
use DDDStarterPack\Domain\Aggregate\EntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value) {
            return null;
        }

        return $value instanceof EntityId ? $value->id() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = $this->prepareValue($value);

        /** @var int|string $value */
        if (!$this->isValidUuid($value) && !$this->isCustomValid()) {
            return $value;
        }

        $className = $this->getFQCN();

        return $className::createFrom($value);
    }

    /**
     * @param mixed $value
     *
     * @return int|string
     */
    private function prepareValue($value)
    {
        if (is_int($value)) {
            return intval($value);
        }

        return (string) $value;
    }

    /**
     * @param int|string $value
     *
     * @return bool
     */
    private function isValidUuid($value): bool
    {
        if (is_string($value) && BasicEntityId::isValidUuid($value)) {
            return true;
        }

        return false;
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
