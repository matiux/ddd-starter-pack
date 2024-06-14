<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Service;

use DDDStarterPack\Exception\TransactionFailedException;
use DDDStarterPack\Service\Doctrine\DoctrineTransactionalSession;
use DDDStarterPack\Service\Service;
use DDDStarterPack\Service\TransactionalService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tests\Tool\EntityManagerBuilder;

class TransactionalServiceTest extends TestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        $this->em = EntityManagerBuilder::create()->getEntityManager();
    }

    /**
     * @test
     */
    public function service_could_be_atomically(): void
    {
        self::expectException(TransactionFailedException::class);
        self::expectExceptionMessage('RepoA is failed so the whole transaction is failed');

        $repoA = \Mockery::mock(Repository::class)
            ->shouldReceive('add')
            ->andThrow(new \Exception('RepoA is failed so the whole transaction is failed'))
            ->getMock();

        $repoB = \Mockery::mock(Repository::class);
        $registry = \Mockery::mock(ManagerRegistry::class)
            ->shouldReceive('resetManager')
            ->getMock();

        $service = new AtomicallyService($repoA, $repoB);

        /** @var DoctrineTransactionalSession<void> $doctrineTransactionalSession */
        $doctrineTransactionalSession = new DoctrineTransactionalSession($this->em, $registry);

        /**
         * !!! This is only a test.
         * This kind of bind should depend on a DIC as it is an infrastructural concern.
         * This test only serves to show how transactional abstraction works.
         */
        $transactionalAtomicallyService = new TransactionalService(
            $service,
            $doctrineTransactionalSession,
        );

        $request = new Request(['foo' => 'bar']);

        $transactionalAtomicallyService->execute($request);
    }
}

class Request
{
    public function __construct(
        private array $data,
    ) {}

    public function data(): array
    {
        return $this->data;
    }
}

interface Repository
{
    public function add(array $data): void;
}

/**
 * @implements Service<Request, void>
 */
class AtomicallyService implements Service
{
    public function __construct(
        protected Repository $repoA,
        protected Repository $repoB,
    ) {}

    public function execute($request): void
    {
        $this->repoA->add($request->data());
    }
}
