<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

/**
 * @template I of mixed
 */
interface GenericId
{
    public static function new(): static;

    /**
     * @template T of I
     *
     * @param T $id
     *
     * @return static
     */
    public static function from($id): static;

    /**
     * @template T of I
     *
     * @param null|T $id
     *
     * @return null|static
     */
    public static function tryFrom($id): null|static;

    /**
     * @return I
     */
    public function value();

    public function equals(GenericId $entityId): bool;
}
