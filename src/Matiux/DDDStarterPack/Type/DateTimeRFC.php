<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

class DateTimeRFC extends \DateTimeImmutable
{
    /**
     * Based on DateTimeInterface::RFC3339_EXTENDED = Y-m-d\TH:i:s.vP
     * v = Milliseconds
     * u = Microseconds.
     */
    public const FORMAT = 'Y-m-d\TH:i:s.uP';
    public const NO_TZ_FORMAT = 'Y-m-d H:i:s.u';

    final public function __construct(string $datetime = 'now', ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }

    /**
     * @param string $dateTime
     *
     * @throws \Exception
     *
     * @return static
     */
    public static function createFrom(string $dateTime, ?\DateTimeZone $timezone = null): static
    {
        if (!$date = static::createFromFormat(self::FORMAT, $dateTime, $timezone)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $dateTime));
        }

        return new static($date->format(self::FORMAT), $timezone);
    }

    public function value(): string
    {
        return $this->__toString();
    }
}
