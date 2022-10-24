<?php

declare(strict_types=1);

namespace Tests\Support\Model\Doctrine;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplier;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplierRegistry;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericPaginationApplier;

class DoctrinePaginationApplier extends DoctrineGenericPaginationApplier implements FilterApplier
{
    protected function pageKey(): string
    {
        return 'page';
    }

    protected function perPageKey(): string
    {
        return 'per_page';
    }

    public function supports(FilterApplierRegistry $filterApplierRegistry): bool
    {
        return true;
    }
}
