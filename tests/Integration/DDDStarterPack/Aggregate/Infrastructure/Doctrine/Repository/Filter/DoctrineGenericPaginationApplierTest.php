<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Aggregate\Domain\Repository\Test\DoctrineUtil;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Doctrine\DoctrinePaginationApplier;
use Tests\Support\Model\Person;
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

        $registryBuilder = new FilterAppliersRegistryBuilder();
        $registryBuilder->addApplier(new DoctrinePaginationApplier());
        $filterAppliersRegistry = $registryBuilder->build(['page' => $page, 'per_page' => $perPage]);
        $filterAppliersRegistry->applyToTarget($qb);

        $expected = sprintf(
            'SELECT %s FROM %s %s',
            'p',
            Person::class,
            'p',
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);

        self::assertEquals($offset, $qb->getFirstResult());
        self::assertEquals($perPage, $qb->getMaxResults());
    }
}
