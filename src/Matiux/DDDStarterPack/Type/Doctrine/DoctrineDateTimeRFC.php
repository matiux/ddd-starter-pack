<?php

declare(strict_types=1);

namespace DDDStarterPack\Type\Doctrine;

use DDDStarterPack\Type\DateTimeRFC;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;

class DoctrineDateTimeRFC extends DateTimeImmutableType
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): mixed
    {
        return 'DATETIME(6)';
    }

    /**
     * @param null|DateTimeRFC $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    #[\Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if ('UTC' !== $value->getTimezone()->getName()) {
            $value = $value->setTimezone(new \DateTimeZone('UTC'));
        }

        return $value->format(DateTimeRFC::NO_TZ_FORMAT);
    }

    #[\Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): DateTimeRFC|null
    {
        if (null === $value) {
            return $value;
        }

        $tz = new \DateTimeZone(date_default_timezone_get());

        if ($value instanceof \DateTimeInterface) {
            if ($tz->getName() !== $value->getTimezone()->getName()) {
                $value = $value->setTimezone($tz);
            }

            $date = \DateTimeImmutable::createFromInterface($value);

            return DateTimeRFC::from($date->format(DateTimeRFC::FORMAT));
        }

        $converted = DateTimeRFC::createFromFormat(
            DateTimeRFC::NO_TZ_FORMAT,
            $value,
            new \DateTimeZone('UTC'),
        );

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                DateTimeRFC::class,
                $platform->getDateTimeFormatString(),
            );
        }

        $date = $converted->setTimezone($tz);

        return DateTimeRFC::from($date->format(DateTimeRFC::FORMAT));
    }
}
