<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineJsonWhereLikeFilterApplier;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Repository\Test\DoctrineUtil;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\EntityManagerBuilder;

class DoctrineJsonWhereLikeFilterApplierTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_supports_multiple_filters(): void
    {
        $requestedFilters = ['address' => "O'Connell Street Lower"];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $applier = new DoctrinePersonJsonWhereLikeFilterApplier();
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

        $applier = new DoctrinePersonJsonWhereLikeFilterApplier();
        self::assertFalse($applier->supports($appliersRegistry));
    }

    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function it_should_apply_where_like_filters(): void
    {
        $requestedFilters = ['address' => 'Connell', 'height' => 1.80, 'surname' => 'Galacci'];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);
        $applier = new DoctrinePersonJsonWhereLikeFilterApplier();

        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')->from(Person::class, 'p');

        $applier->applyTo($qb, $appliersRegistry);

        $expected = sprintf(
            "SELECT p 
            FROM %s p 
            WHERE 
                JSON_SEARCH(LOWER(p.data), 'one', :address, NULL, LOWER('$[*].address')) IS NOT NULL 
                AND JSON_SEARCH(LOWER(p.data), 'one', :height, NULL, LOWER('$[*].general.height')) IS NOT NULL
                AND JSON_SEARCH(p.data, 'one', :surname, NULL, '$[*].general.surname') IS NOT NULL",
            Person::class,
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);

        $addressValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(0)->getValue();
        self::assertEquals('%connell%', $addressValue);

        $vatValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(1)->getValue();
        self::assertEquals('%180%', $vatValue);

        $surnameValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(2)->getValue();
        self::assertEquals('%Galacci%', $surnameValue);
    }

    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function it_should_throw_an_exception_if_the_filter_value_is_not_valid(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('When using the JsonWhereLike applier, the filter value must be an integer, float, or string. [key: address - val type: array - val: ["foo"]]');

        $requestedFilters = ['address' => ['foo']];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);
        $applier = new DoctrinePersonJsonWhereLikeFilterApplier();

        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')->from(Person::class, 'p');

        $applier->applyTo($qb, $appliersRegistry);
    }
}

class DoctrinePersonJsonWhereLikeFilterApplier extends DoctrineJsonWhereLikeFilterApplier
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
            'height' => [
                'path' => '$[*].general.height',
                'column' => 'data',
                'preProcessor' => fn (float $height) => (int) ($height * 100),
            ],
            'surname' => [
                'path' => '$[*].general.surname',
                'column' => 'data',
                'caseSensitive' => true,
            ],
        ];
    }
}
