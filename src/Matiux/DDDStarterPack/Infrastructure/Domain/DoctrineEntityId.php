<?php

namespace DDDStarterPack\Infrastructure\Domain;

use DDDStarterPack\Domain\Model\BasicEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value ? $value->id() : null;
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

        $className = $this->getFQCN();

        return $className::create($value);
    }

    abstract protected function getNamespace(): string;

    protected function getFQCN(): string
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }
}
