<?php

namespace DDDStarterPack\Infrastructure\Domain;

use DDDStarterPack\Domain\Model\BasicEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value) {
            return null;
        }

        return $value instanceof BasicEntityId ? $value->id() : $value;
    }

    private function isValidUuid($value): bool
    {
        if (is_string($value) && BasicEntityId::isValidUuid($value)) {
            return true;
        }

        return false;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!$this->isValidUuid($value)) {
            return $value;
        }

        $className = $this->getNamespace() . '\\' . $this->getName();

        return $className::create($value);
    }

    abstract protected function getNamespace(): string;
}
