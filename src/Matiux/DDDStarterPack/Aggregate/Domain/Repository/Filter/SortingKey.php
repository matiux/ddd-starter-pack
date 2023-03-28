<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

use Webmozart\Assert\Assert;

abstract class SortingKey
{
    /**
     * @return array<array-key, string>
     */
    abstract public static function keys(): array;

    public static function from(string $sortKey): string
    {
        $classConstants = (new \ReflectionClass(static::class))->getConstants();

        Assert::keyExists($classConstants, $sortKey, sprintf('Chiave di ordinamento `%s` non valida', $sortKey));

        return $sortKey;
    }
}
