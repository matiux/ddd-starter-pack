<?php

declare(strict_types=1);

namespace Tests\Support\Model\Doctrine;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericSortApplier;

class DoctrineSortApplier extends DoctrineGenericSortApplier implements FilterParamsApplier
{
    public function __construct()
    {
        $this->fieldsMap = ['name' => 'p.name'];
    }

    protected function sortDirectionKey(): string
    {
        return 'sort_direction';
    }

    protected function sortKey(): string
    {
        return 'sort_field';
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
