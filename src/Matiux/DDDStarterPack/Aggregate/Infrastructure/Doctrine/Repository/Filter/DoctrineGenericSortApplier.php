<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use LogicException;

abstract class DoctrineGenericSortApplier extends DoctrineFilterParamsApplier
{
    protected const KEY = 'sorting';

    /** @var array<string, string> */
    protected array $fieldsMap;

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
        try {
            $sortField = (string) $filterParams->getFilterValueForKey($this->sortKey());
            $sortDirection = (string) $filterParams->getFilterValueForKey($this->sortDirectionKey(), 'ASC');

            if (!array_key_exists($sortField, $this->fieldsMap)) {
                throw new LogicException(sprintf('Invalid sort key: %s', $sortField));
            }

            $target->orderBy($this->fieldsMap[$sortField], $sortDirection);
        } catch (InvalidArgumentException $e) {
            return;
        }
    }
}
