<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class DoctrineDateTimeRFC extends StringType
{
    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param null|DateTimeRFC $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): null|string
    {
        if (is_null($value)) {
            return null;
        }

        return (string) $value;
    }

    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param null|string      $value
     * @param AbstractPlatform $platform
     *
     * @throws \Exception
     *
     * @return null|DateTimeRFC
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): null|DateTimeRFC
    {
        if (is_null($value)) {
            return null;
        }

        return DateTimeRFC::createFrom($value);
    }
}
