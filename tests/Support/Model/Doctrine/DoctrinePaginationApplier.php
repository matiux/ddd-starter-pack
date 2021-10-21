<?php

declare(strict_types=1);

namespace Tests\Support\Model\Doctrine;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericPaginationApplier;

class DoctrinePaginationApplier extends DoctrineGenericPaginationApplier implements FilterParamsApplier
{
    protected function pageKey(): string
    {
        return 'page';
    }

    protected function perPageKey(): string
    {
        return 'per_page';
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
