<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository;

/**
 * @template E
 * @template I
 */
interface IdentifiableRepository
{
    /**
     * @param I $id
     *
     * @return null|E
     */
    public function ofId($id);

    /**
     * @return I
     */
    public function nextIdentity();
}
