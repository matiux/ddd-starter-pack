<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Doctrine\Repository\Filter\DoctrineWhereEqualsFilterApplier;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Repository\Test\DoctrineUtil;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\EntityManagerBuilder;

class DoctrineWhereEqualsFilterApplierTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_supports_multiple_filters(): void
    {
        $requestedFilters = ['surname' => 'Galacci'];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);

        $applier = new DoctrinePersonWhereEqualsFilterApplier();
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

        $applier = new DoctrinePersonWhereEqualsFilterApplier();
        self::assertFalse($applier->supports($appliersRegistry));
    }

    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function it_should_apply_where_equals_filters(): void
    {
        $requestedFilters = ['surname' => 'Galacci', 'name' => 'Matteo', 'height' => 1.80];
        $registryBuilder = new FilterAppliersRegistryBuilder();
        $appliersRegistry = $registryBuilder->build($requestedFilters);
        $applier = new DoctrinePersonWhereEqualsFilterApplier();

        $em = EntityManagerBuilder::create()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')->from(Person::class, 'p');

        $applier->applyTo($qb, $appliersRegistry);

        $expected = sprintf(
            'SELECT p FROM %s p WHERE p.name = :name AND p.surname = :surname AND p.height = :height',
            Person::class,
        );

        /** @var string $actual */
        $actual = $qb->getQuery()->getDQL();
        DoctrineUtil::assertDQLEquals($expected, $actual);

        $nameValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(0)->getValue();
        self::assertEquals('Matteo', $nameValue);

        $surnameValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(1)->getValue();
        self::assertEquals('Galacci', $surnameValue);

        $heightValue = $qb->getQuery()->getParameters()->getIterator()->offsetGet(2)->getValue();
        self::assertEquals(180, $heightValue);
    }
}

class DoctrinePersonWhereEqualsFilterApplier extends DoctrineWhereEqualsFilterApplier
{
    protected function getModelAlias(): string
    {
        return 'p';
    }

    protected function getSupportedFilters(): array
    {
        return [
            'name',
            'surname',
            'height' => [
                'preProcessor' => fn (float $height) => (int) ($height * 100),
            ],
        ];
    }
}
