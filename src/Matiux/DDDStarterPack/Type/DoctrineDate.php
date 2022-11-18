<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;

class DoctrineDate extends DateType
{
    /**
     * @psalm-suppress MoreSpecificImplementedParamType
     *
     * @param null|Date        $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): null|string
    {
        if (is_null($value)) {
            return null;
        }

        return $value->format(Date::FORMAT);
    }

    /**
     * @psalm-suppress all
     *
     * @param null|string      $value
     * @param AbstractPlatform $platform
     *
     * @throws \Exception
     *
     * @return null|Date
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): null|Date
    {
        if (null === $value) {
            return $value;
        }

        $converted = Date::createFromFormat(
            Date::FORMAT,
            $value
        );

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                Date::class,
                $platform->getDateTimeFormatString()
            );
        }

        return Date::createFrom($converted->format(Date::FORMAT));
    }
}
