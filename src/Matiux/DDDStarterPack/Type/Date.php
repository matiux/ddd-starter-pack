<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

/**
 * @psalm-consistent-constructor
 */
class Date extends \DateTimeImmutable
{
    public const FORMAT = 'Y-m-d';

    public function __construct(
        string $datetime = 'now',
        ?\DateTimeZone $timezone = null,
    ) {
        parent::__construct($datetime, $timezone);
    }

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }

    /**
     * @deprecated use DateTimeRFC::create() instead
     */
    public static function createFrom(string $date, ?\DateTimeZone $timezone = null): static
    {
        return static::create($date, $timezone);
    }

    public static function create(string $date, ?\DateTimeZone $timezone = null): static
    {
        if (!$result = self::createFromFormat(self::FORMAT, $date, $timezone)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $date));
        }

        return new static($result->format(self::FORMAT), $timezone);
    }

    public static function createUTC(string $dateTime): static
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
}
