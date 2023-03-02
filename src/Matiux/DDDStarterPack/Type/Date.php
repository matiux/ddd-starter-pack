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
     * @throws \Exception
     */
    public static function createFrom(string $date): static
    {
        if (!$result = self::createFromFormat(self::FORMAT, $date, new \DateTimeZone('+00:00'))) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $date));
        }

        return new static($result->format(self::FORMAT));
    }

    public function value(): string
    {
        return $this->__toString();
    }
}
