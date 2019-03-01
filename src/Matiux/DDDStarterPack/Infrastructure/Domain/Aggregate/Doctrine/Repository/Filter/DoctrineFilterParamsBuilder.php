<?php

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsBuilder;

class DoctrineFilterParamsBuilder extends FilterParamsBuilder
{
    public function addApplier(FilterParamsApplier $filterParamsApplier): void
    {
        $this->doAddApplier($filterParamsApplier);
    }

    protected function doAddApplier(DoctrineFilterParamsApplier $filterParamsApplier)
    {
        parent::addApplier($filterParamsApplier);
    }
}
