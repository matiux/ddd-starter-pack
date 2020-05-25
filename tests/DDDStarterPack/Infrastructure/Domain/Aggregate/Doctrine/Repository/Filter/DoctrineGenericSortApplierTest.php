<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsBuilder;
use DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter\DoctrineGenericSortApplier;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\DoctrineUtil;
use Tests\Tool\EntityManagerBuilder;

class DoctrineGenericSortApplierTest extends TestCase
{
    /**
     * @test
     */
    public function apply_sorting_to_query_builder(): void
    {
        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p')
            ->from(Person::class, 'p');

        $expected = sprintf('SELECT %s FROM %s %s', 'p', Person::class, 'p');

        DoctrineUtil::assertDQLEquals($expected, (string) $qb->getQuery()->getDQL());

        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DoctrineSortApplier());
        $filterParams = $filterParamsBuilder->build(['sort_field' => 'value', 'sort_direction' => 'ASC']);
        $filterParams->applyTo($qb);

        $expected = sprintf(
            'SELECT %s FROM %s %s
            ORDER BY %s.name ASC',
            'p',
            Person::class,
            'p',
            'p'
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);
    }
}

class DoctrineSortApplier extends DoctrineGenericSortApplier implements FilterParamsApplier
{
    public function __construct()
    {
        $this->fieldsMap = ['value' => 'p.name'];
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
