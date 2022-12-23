<?php

declare(strict_types=1);

namespace Tests\Support\Model\Doctrine;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericPaginationApplier;

class DoctrinePaginationApplier extends DoctrineGenericPaginationApplier
{
    protected function pageKey(): string
    {
        return 'page';
    }

    protected function perPageKey(): string
    {
        return 'per_page';
    }

    public function supports(FilterAppliersRegistry $filterAppliers): bool
    {
        return true;
    }
}
