<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

final class Date extends \DateTimeImmutable
{
    public const FORMAT = 'Y-m-d';

    public function __toString(): string
    {
        return $this->format(self::FORMAT);
    }

    /**
     * @param string $date
     *
     * @throws \Exception
     *
     * @return self
     */
    public static function createFrom(string $date): self
    {
        if (!$result = self::createFromFormat(self::FORMAT, $date)) {
            throw new \InvalidArgumentException(sprintf('Data non valida: %s', $date));
        }

        return new self($result->format(self::FORMAT));
    }

    public function value(): string
    {
        return $this->__toString();
    }
}
