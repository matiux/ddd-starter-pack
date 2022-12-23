<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter\Test;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterBuilder;
use DDDStarterPack\Aggregate\Domain\Repository\Test\DoctrineUtil;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class FilterApplierScenario
{
    private array $filters;

    public function __construct(
        private TestCase $testCase,
        private FilterBuilder $filterParamBuilder,
        private QueryBuilder $queryBuilder,
    ) {
    }

    public function whenFiltersApplied(array $filters): self
    {
        $this->filters = $filters;

        $filterParams = $this->filterParamBuilder->build($filters);
        $filterParams->applyToTarget($this->queryBuilder);

        return $this;
    }

    public function thenQueryBuilt(string $expectedQuery): self
    {
        /** @var string $actual */
        $actual = $this->queryBuilder->getQuery()->getDQL();

        DoctrineUtil::assertDQLEquals($expectedQuery, $actual);

        return $this;
    }

    /**
     * @psalm-suppress MixedArgument
     *
     * Verify that the values of the requested filters are equal to the values bind into the query.
     */
    public function andFilterValuesMatch(): self
    {
        $this->testCase::assertEquals(
            array_values($this->filters),
            array_map(
                fn (string $val) => trim($val, '%'),
                array_map(
                    fn (Parameter $val) => (string) $val->getValue(),
                    $this->queryBuilder->getParameters()->toArray(),
                ),
            ),
        );

        return $this;
    }

    public function queryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}