<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Repository\Doctrine\Repository;

use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineGenericSortApplier;

class DoctrinePersonSortApplier extends DoctrineGenericSortApplier
{
    protected function getFieldsMap(): array
    {
        return ['name' => 'p.name'];
    }
}
