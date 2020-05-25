<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

/**
 * Class ModelCriteria.
 *
 * @psalm-suppress MissingConstructor
 */
class ModelCriteria
{
    /** @var Criteria[] */
    private $criteria = [];

    /** @var Sorting */
    private $order;

    /** @var int */
    private $page = 1;

    /** @var int */
    private $limit = 0;

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function addCriteria(Criteria $criteria): void
    {
        array_push($this->criteria, $criteria);
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return array|Criteria[]
     */
    public function criteria(): array
    {
        return $this->criteria;
    }

    public function addSorting(Sorting $sort): void
    {
        $this->order = $sort;
    }

    /**
     * @return Sorting
     */
    public function sorting(): ?Sorting
    {
        return $this->order;
    }
}
