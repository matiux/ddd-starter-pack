<?php

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
        if (!$this->isValidUuid($value) && !$this->isCustomValid()) {
            return $value;
        }

        $className = $this->getFQCN();

        return $className::create($value);
    }

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

    protected function getFQCN(): string
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }

    abstract protected function getNamespace(): string;
}
