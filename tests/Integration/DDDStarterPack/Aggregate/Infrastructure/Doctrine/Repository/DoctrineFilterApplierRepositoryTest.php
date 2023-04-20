<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistryBuilder;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\PaginatorI;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\DoctrineFilterApplierRepository;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter\DoctrineGenericPaginationApplier;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;
use Tests\Tool\EntityManagerBuilder;

class DoctrineFilterApplierRepositoryTest extends TestCase
{
    private MyFilterApplierRepository $repository;
    private EntityManager $em;

    protected function setUp(): void
    {
        $this->em = EntityManagerBuilder::create()->getEntityManager();

        $this->em->getConnection()->beginTransaction();

        $registry = \Mockery::mock(Registry::class)
            ->shouldReceive('getManagerForClass')->andReturn($this->em)
            ->getMock();

        $this->repository = new MyFilterApplierRepository($registry, Person::class);
    }

    protected function tearDown(): void
    {
        $this->em->clear();
        $this->em->getConnection()->rollBack();
    }

    /**
     * @test
     */
    public function it_should_use_filterparams_in_repository(): void
    {
        $this->repository->aggiungi($p1 = Person::crea(PersonId::create(), 'A_Mat', 35));
        $this->repository->aggiungi(Person::crea(PersonId::create(), 'B_Met', 35));
        $this->repository->aggiungi(Person::crea(PersonId::create(), 'Z_Teo', 35));

        $registryBuilder = new FilterAppliersRegistryBuilder();
        $registryBuilder->addApplier(new DoctrinePersonSortApplier());
        $registryBuilder->addApplier(new DoctrineGenericPaginationApplier());

        $filterAppliersRegistry = $registryBuilder->build(
            [
                'sort_field' => 'name',
                'sort_direction' => 'DESC',
                'page' => 2,
                'per_page' => 2,
            ],
        );

        $items = $this->repository->byFilterParamsWithPagination($filterAppliersRegistry);

        self::assertInstanceOf(PaginatorI::class, $items);
        self::assertCount(1, $items);
        self::assertSame($p1, $items->current());

        $items = $this->repository->byFilterParams($filterAppliersRegistry);

        self::assertCount(1, $items);
        self::assertSame($p1, $items[0]);
    }
}

/**
 * @extends DoctrineFilterApplierRepository<Person>
 */
class MyFilterApplierRepository extends DoctrineFilterApplierRepository
{
    public function aggiungi(Person $person): void
    {
        $this->em->persist($person);
        $this->em->flush();
    }

    public function byFilterParamsWithPagination(FilterAppliersRegistry $filterParams): PaginatorI
    {
        return $this->doByFilterParamsWithPagination($filterParams);
    }

    public function byFilterParams(FilterAppliersRegistry $filterParams): array
    {
        return $this->doByFilterParams($filterParams);
    }

    protected function getEntityAliasName(): string
    {
        return 'p';
    }
}