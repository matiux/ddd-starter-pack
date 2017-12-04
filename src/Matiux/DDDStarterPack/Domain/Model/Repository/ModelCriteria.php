<?php

namespace DDDStarterPack\Domain\Model\Repository;

class ModelCriteria
{
    /**
     * @var Criteria[]
     */
    private $criteria = [];

    /**
     * @var Sorting
     */
    private $order;
    private $page = 1;
    private $limit = 0;

    public function setPage(int $page)
    {
        $this->page = $page;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    public function addCriteria(Criteria $criteria)
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
     * @return Criteria[]|array
     */
    public function criteria(): array
    {
        return $this->criteria;
    }

    public function addSorting(Sorting $sort)
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
