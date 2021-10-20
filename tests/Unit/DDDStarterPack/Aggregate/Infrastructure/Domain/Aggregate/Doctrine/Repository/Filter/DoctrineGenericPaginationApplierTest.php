<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsBuilder;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericPaginationApplier;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\DoctrineUtil;
use Tests\Tool\EntityManagerBuilder;

class DoctrineGenericPaginationApplierTest extends TestCase
{
    /**
     * @return array<array-key, array<array-key, int>>
     */
    public function paginationDataProvider(): array
    {
        return [
            [1, 10, 0],
            [2, 10, 10],
            [3, 10, 20],
        ];
    }

    /**
     * @test
     *
     * @dataProvider paginationDataProvider
     */
    public function apply_pagination_to_query_builder(int $page, int $perPage, int $offset): void
    {
        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p')->from(Person::class, 'p');

        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DoctrinePaginationApplier());
        $filterParams = $filterParamsBuilder->build(['page' => $page, 'per_page' => $perPage]);
        $filterParams->applyTo($qb);

        $expected = sprintf(
            'SELECT %s FROM %s %s',
            'p',
            Person::class,
            'p'
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);

        self::assertEquals($offset, $qb->getFirstResult());
        self::assertEquals($perPage, $qb->getMaxResults());
    }
}

class DoctrinePaginationApplier extends DoctrineGenericPaginationApplier implements FilterParamsApplier
{
    protected function pageKey(): string
    {
        return 'page';
    }

    protected function perPageKey(): string
    {
        return 'per_page';
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
