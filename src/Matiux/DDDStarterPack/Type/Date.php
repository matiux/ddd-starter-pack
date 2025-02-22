<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

/**
 * @psalm-consistent-constructor
 */
class Date extends \DateTimeImmutable
{
    public const FORMAT = 'Y-m-d';

    final public function __construct(string $datetime = 'now', null|\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }

    public static function UTC(string $datetime = 'now'): static
    {
        return new static($datetime,new \DateTimeZone('UTC'));
    }

    /**
     * @deprecated use DateTimeRFC::from() instead
     */
    public static function createFrom(string $date, null|\DateTimeZone $timezone = null): static
    {
        return static::from($date, $timezone);
    }

    public static function from(string $date, null|\DateTimeZone $timezone = null): static
    {
        if (!$result = self::createFromFormat(self::FORMAT, $date, $timezone)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $date));
        }

        return new static($result->format(self::FORMAT), $timezone);
    }

    public static function UTCfrom(string $dateTime): static
    {
        $tz = new \DateTimeZone('UTC');

        if (!$date = static::createFromFormat(self::FORMAT, $dateTime, $tz)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $dateTime));
        }

        return new static($date->format(self::FORMAT), $tz);
    }

    public function value(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }
}
