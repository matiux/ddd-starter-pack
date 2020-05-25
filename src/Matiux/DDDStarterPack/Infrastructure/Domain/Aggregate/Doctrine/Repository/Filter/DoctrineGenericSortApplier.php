<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use Doctrine\ORM\QueryBuilder;
use LogicException;

abstract class DoctrineGenericSortApplier extends DoctrineFilterParamsApplier
{
    protected const KEY = 'sorting';

    /** @var array<string, string> */
    protected $fieldsMap;

    abstract protected function sortDirectionKey(): string;

    abstract protected function sortKey(): string;

    public function key(): string
    {
        return self::KEY;
    }

    /**
     * @param QueryBuilder $target
     */
    public function apply($target, FilterParams $filterParams): void
    {
        /** @var null|string $sortField */
        $sortField = $filterParams->get($this->sortKey());

        /** @var null|string $sortDirection */
        $sortDirection = $filterParams->get($this->sortDirectionKey());

        if (!$sortField) {
            return;
        }

        if (!$sortDirection) {
            $sortDirection = 'ASC';
        }

        if (!array_key_exists($sortField, $this->fieldsMap)) {
            throw new LogicException("\"{$sortField}\" is not a valid sorting field");
        }

        $target->orderBy($this->fieldsMap[$sortField], $sortDirection);
    }
}
