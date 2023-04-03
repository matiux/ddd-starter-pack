<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericSortApplier;

class DoctrinePersonSortApplier extends DoctrineGenericSortApplier
{
    protected function getFieldsMap(): array
    {
        return ['name' => 'p.name'];
    }
}
