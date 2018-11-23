<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Model\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Domain\Model\Repository\Filter\FilterParamsBuilder;

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
