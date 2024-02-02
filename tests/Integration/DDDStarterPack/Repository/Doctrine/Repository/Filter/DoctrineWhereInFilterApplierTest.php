<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineWhereInFilterApplier;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Repository\Test\DoctrineUtil;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\EntityManagerBuilder;

class DoctrineWhereInFilterApplierTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_supports_multiple_filters(): void
    {
        $requestedFilters = ['height' => [1.5, 1.8, 2.0]];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $applier = new DoctrinePersonWhereInFilterApplier();
        self::assertTrue($applier->supports($appliersRegistry));
    }

    /**
     * @test
     */
    public function it_should_return_false_if_not_supports(): void
    {
        $requestedFilters = ['weight' => 90];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $applier = new DoctrinePersonWhereInFilterApplier();
        self::assertFalse($applier->supports($appliersRegistry));
    }

    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function it_should_apply_where_in_filters(): void
    {
        $requestedFilters = ['height' => [1.5, 1.8, 2.0], 'color' => ['red', 'green', 'white']];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);
        $applier = new DoctrinePersonWhereInFilterApplier();

        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')->from(Person::class, 'p');

        $applier->applyTo($qb, $appliersRegistry);

        $expected = sprintf(
            'SELECT p FROM %s p WHERE p.color IN (:color) AND p.height IN (:height)',
            Person::class,
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);

        $colorValues = $qb->getQuery()->getParameters()->getIterator()->offsetGet(0)->getValue();
        self::assertIsArray($colorValues);
        self::assertEquals(['red', 'green', 'white'], $colorValues);

        $heightValues = $qb->getQuery()->getParameters()->getIterator()->offsetGet(1)->getValue();
        self::assertIsArray($heightValues);
        self::assertEquals([150, 180, 200], $heightValues);
    }
}

class DoctrinePersonWhereInFilterApplier extends DoctrineWhereInFilterApplier
{
    protected function getModelAlias(): string
    {
        return 'p';
    }

    protected function getSupportedFilters(): array
    {
        return [
            'color',
            'height' => [
                'preProcessor' => fn (float $height) => (int) ($height * 100),
            ],
        ];
    }
}
