<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Aggregate\Domain\Repository\Test\DoctrineUtil;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericSortApplier;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Tool\EntityManagerBuilder;

class DoctrineGenericSortApplierTest extends TestCase
{
    private QueryBuilder $qb;

    protected function setUp(): void
    {
        $em = EntityManagerBuilder::create()->getEntityManager();
        $this->qb = $em->createQueryBuilder();
    }

    /**
     * @test
     */
    public function apply_sorting_to_query_builder(): void
    {
        $this->qb->select('p')->from(Person::class, 'p');

        $expected = sprintf('SELECT %s FROM %s %s', 'p', Person::class, 'p');

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );

        $registryBuilder = new FilterAppliersRegistryBuilder();
        $registryBuilder->addApplier(new DoctrineGenericSortApplier(['name' => 'p.name']));

        $filterAppliersRegistry = $registryBuilder->build(['sort_field' => 'name', 'sort_direction' => 'ASC']);
        $filterAppliersRegistry->applyToTarget($this->qb);

        $expected = sprintf(
            'SELECT %s FROM %s %s ORDER BY %s.name ASC',
            'p',
            Person::class,
            'p',
            'p',
        );

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );
    }

    /**
     * @test
     */
    public function it_should_not_apply_filter_if_sort_field_is_null(): void
    {
        $this->qb->select('p')->from(Person::class, 'p');

        $expected = sprintf('SELECT %s FROM %s %s', 'p', Person::class, 'p');

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );

        $filterParamsBuilder = new FilterAppliersRegistryBuilder();
        $filterParamsBuilder->addApplier(new DoctrineGenericSortApplier(['name' => 'p.name']));

        $filterParams = $filterParamsBuilder->build(['sort_field' => null, 'sort_direction' => 'ASC']);
        $filterParams->applyToTarget($this->qb);

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );
    }

    /**
     * @test
     */
    public function it_should_use_default_sort_direction_if_not_present(): void
    {
        $this->qb->select('p')->from(Person::class, 'p');

        $expected = sprintf('SELECT %s FROM %s %s', 'p', Person::class, 'p');

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );

        $filterParamsBuilder = new FilterAppliersRegistryBuilder();
        $filterParamsBuilder->addApplier(new DoctrineGenericSortApplier(['name' => 'p.name']));

        $filterParams = $filterParamsBuilder->build(['sort_field' => 'name']);
        $filterParams->applyToTarget($this->qb);

        $expected = sprintf(
            'SELECT %s FROM %s %s ORDER BY %s.name ASC',
            'p',
            Person::class,
            'p',
            'p',
        );

        DoctrineUtil::assertDQLEquals(
            $expected,
            (string) $this->qb->getQuery()->getDQL(),
        );
    }
}
