<?php

namespace DddStarterPack\Domain\Model\Exception\Repository;

class ModelCriteriaBuilder
{
    private $andCriteria = [];
    private $orCriteria = [];

    private $sorting;
    private $page = 1;
    private $limit = 0;

    private function __construct()
    {
    }

    public static function aModelCriteria(): ModelCriteriaBuilder
    {
        return new static();
    }

    public function withPage(int $page): ModelCriteriaBuilder
    {
        $this->page = $page;

        return $this;
    }

    public function withLimit(int $limit): ModelCriteriaBuilder
    {
        $this->limit = $limit;

        return $this;
    }

    public function withOrCriterion(string $field, $value, $operator = '='): ModelCriteriaBuilder
    {
        $criterion = new Criterion($field, $value, $operator);

        array_push($this->orCriteria, $criterion);

        return $this;
    }

    public function withAndCriteria(string $field, $value, $operator = '='): ModelCriteriaBuilder
    {
        $criterion = new Criterion($field, $value, $operator);

        array_push($this->andCriteria, $criterion);

        return $this;
    }

    public function withOrder($sortField, $sortDirection): ModelCriteriaBuilder
    {
        $order = new Sorting($sortField, $sortDirection);

        $this->sorting = $order;

        return $this;
    }

    public function build(): ModelCriteria
    {
        $modelCriteria = new ModelCriteria();
        $modelCriteria->setLimit($this->limit);
        $modelCriteria->setPage($this->page);

        if ($this->andCriteria) {

            $andCriteria = new AndCriteria();

            foreach ($this->andCriteria as $criterion) {

                $andCriteria->add($criterion);
            }

            $modelCriteria->addCriteria($andCriteria);
        }

        if ($this->orCriteria) {

            $orCriteria = new OrCriteria();

            foreach ($this->orCriteria as $criterion) {

                $orCriteria->add($criterion);
            }

            $modelCriteria->addCriteria($orCriteria);
        }

        if ($this->sorting) {
            $modelCriteria->addSorting($this->sorting);
        }

        return $modelCriteria;
    }
}
