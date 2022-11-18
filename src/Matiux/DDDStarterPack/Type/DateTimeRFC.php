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

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }

    /**
     * @param string $dateTime
     *
     * @throws \Exception
     *
     * @return self
     */
    public static function createFrom(string $dateTime): self
    {
        if (!$date = self::createFromFormat(self::FORMAT, $dateTime)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $dateTime));
        }

        return new self($date->format(self::FORMAT));
    }

    public function value(): string
    {
        return $this->__toString();
    }
}
