<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineJsonWhereEqualsFilterApplier;
use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineJsonWhereInFilterApplier;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Repository\Test\DoctrineUtil;
use Doctrine\ORM\Query\Parameter;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\EntityManagerBuilder;

class DoctrineJsonWhereInFilterApplierTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_supports_multiple_filters(): void
    {
        $requestedFilters = ['height' => [1.5, 1.8, 2.0]];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $applier = new DoctrinePersonJsonWhereInFilterApplier();
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

        $applier = new DoctrinePersonJsonWhereInFilterApplier();
        self::assertFalse($applier->supports($appliersRegistry));
    }

    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function it_should_apply_where_in_filters(): void
    {
        $requestedFilters = ['height' => [1.5, 1.8, 2.0], 'address' => 'connell'];

        $registryBuilder = new FilterAppliersRegistryBuilder();
        $registryBuilder->addApplier(new DoctrinePersonJsonWhereInFilterApplier());
        $registryBuilder->addApplier(new PersonJsonWhereEqualsFilterApplier());

        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $qb = EntityManagerBuilder::create()->getEntityManager()->createQueryBuilder();
        $qb->select('p')->from(Person::class, 'p');

        $appliersRegistry->applyToTarget($qb);

        $expected = sprintf(
            "SELECT p FROM %s p WHERE (JSON_SEARCH(p.data, 'one', :val0, NULL, '$[*].height') IS NOT NULL 
                    OR
                JSON_SEARCH(p.data, 'one', :val1, NULL, '$[*].height') IS NOT NULL
                    OR
                JSON_SEARCH(p.data, 'one', :val2, NULL, '$[*].height') IS NOT NULL) 
                     AND 
               JSON_SEARCH(p.data, 'one', :address, NULL, '$[*].address') IS NOT NULL",
            Person::class,
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();

        DoctrineUtil::assertDQLEquals($expected, $actual);

        $colors = array_map(fn (Parameter $parameter) => $parameter->getValue(), $qb->getQuery()->getParameters()->toArray());
        self::assertEquals([150, 180, 200, 'connell'], $colors);
    }
}

class DoctrinePersonJsonWhereInFilterApplier extends DoctrineJsonWhereInFilterApplier
{
    protected function getModelAlias(): string
    {
        return 'p';
    }

    protected function getSupportedFilters(): array
    {
        return [
            'height' => [
                'path' => '$[*].height',
                'column' => 'data',
                'preProcessor' => fn (float $height) => (int) ($height * 100),
            ],
        ];
    }
}

class PersonJsonWhereEqualsFilterApplier extends DoctrineJsonWhereEqualsFilterApplier
{
    protected function getModelAlias(): string
    {
        return 'p';
    }

    protected function getSupportedFilters(): array
    {
        return [
            'address' => [
                'path' => '$[*].address',
                'column' => 'data',
            ],
            'vat' => [
                'path' => '$[*].general.vat',
                'column' => 'data',
            ],
        ];
    }
}
