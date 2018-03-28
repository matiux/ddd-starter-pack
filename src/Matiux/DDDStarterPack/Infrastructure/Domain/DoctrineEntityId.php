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
        if (is_string($value) && preg_match(BasicEntityId::UUID_PATTERN, $value) === 1) {
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
