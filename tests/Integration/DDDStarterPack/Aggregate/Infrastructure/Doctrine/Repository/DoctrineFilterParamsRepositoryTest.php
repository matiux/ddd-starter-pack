<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use ArrayObject;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsBuilder;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\DoctrineFilterParamsRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Doctrine\DoctrinePaginationApplier;
use Tests\Support\Model\Doctrine\DoctrineSortApplier;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;
use Tests\Support\Model\PersonPaginator;
use Tests\Tool\EntityManagerBuilder;

class DoctrineFilterParamsRepositoryTest extends TestCase
{
    private MyFilterParamsRepository $repository;
    private EntityManager $em;

    protected function setUp(): void
    {
        $this->em = EntityManagerBuilder::create()->getEntityManager();

        $this->em->getConnection()->beginTransaction();

        $registry = Mockery::mock(Registry::class)
            ->shouldReceive('getManagerForClass')->andReturn($this->em)
            ->getMock();

        $this->repository = new MyFilterParamsRepository($registry, Person::class);
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

        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DoctrineSortApplier());
        $filterParamsBuilder->addApplier(new DoctrinePaginationApplier());

        $filterParams = $filterParamsBuilder->build(['sort_field' => 'name', 'sort_direction' => 'DESC', 'page' => 2, 'per_page' => 2]);

        $items = $this->repository->byFilterParams($filterParams);

        self::assertCount(1, $items);

        $person = $items->current();

        self::assertSame($p1, $person);
    }
}

class MyFilterParamsRepository extends DoctrineFilterParamsRepository
{
    public function aggiungi(Person $person): void
    {
        $this->em->persist($person);
        $this->em->flush();
    }

    public function byFilterParams(FilterParams $filterParams): Paginator
    {
        return $this->doByFilterParams($filterParams);
    }

    protected function createPaginator(QueryBuilder $qb, int $offset, int $limit): PersonPaginator
    {
        $res = (array) $qb->getQuery()->getResult();
        $doctrinePaginator = new DoctrinePaginator($qb);

        /** @var ArrayObject<int, Person> $arrayObject */
        $arrayObject = new ArrayObject($res);

        return new PersonPaginator($arrayObject, $offset, $limit, $doctrinePaginator->count());
    }

    protected function getEntityAliasName(): string
    {
        return 'p';
    }
}