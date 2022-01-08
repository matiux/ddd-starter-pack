<?php

declare(strict_types=1);

namespace Integration\DDDStarterPack\Service\Application;

use DDDStarterPack\Exception\Application\TransactionFailedException;
use DDDStarterPack\Service\Application\TransactionalApplicationService;
use DDDStarterPack\Service\Application\TransactionalSession;
use DDDStarterPack\Service\Domain\Service;
use DDDStarterPack\Service\Infrastructure\Doctrine\DoctrineTransactionalSession;
use Doctrine\ORM\EntityManager;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Tool\EntityManagerBuilder;

class TransactionalApplicationServiceTest extends TestCase
{
    private EntityManager $em;
    private DoctrineTransactionalSession $doctrineTransactionalSession;

    protected function setUp(): void
    {
        $this->em = EntityManagerBuilder::create()->getEntityManager();
        $this->doctrineTransactionalSession = new DoctrineTransactionalSession($this->em);
    }

    /**
     * @test
     */
    public function service_could_be_atomically(): void
    {
        self::expectException(TransactionFailedException::class);
        self::expectExceptionMessage('RepoA is failed so the whole transaction is failed');

        $repoA = Mockery::mock(Repository::class)
            ->shouldReceive('add')
            ->andThrow(new Exception('RepoA is failed so the whole transaction is failed'))
            ->getMock();

        $repoB = Mockery::mock(Repository::class);

        $atomicallyService = new AtomicallyService($repoA, $repoB);

        /**
         * !!! This is only a test.
         * This kind of bind should depend on a DIC as it is an infrastructural concern.
         * This test only serves to show how transactional abstraction works.
         */
        $transactionalAtomicallyService = new TransactionalAtomicallyService(
            $atomicallyService,
            $this->doctrineTransactionalSession
        );

        $request = new Request(['foo' => 'bar']);

        $transactionalAtomicallyService->execute($request);
    }
}

class Request
{
    public function __construct(
        private array $data
    ) {
    }

    public function data(): array
    {
        return $this->data;
    }
}

/**
 * @extends Service<Request>
 */
interface AtomicallyServiceI extends Service
{
    /**
     * @param Request $request
     */
    public function execute($request): void;
}

interface Repository
{
    public function add(array $data): void;
}

class AtomicallyService implements AtomicallyServiceI
{
    public function __construct(
        protected Repository $repoA,
        protected Repository $repoB,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request): void
    {
        $this->repoA->add($request->data());
    }
}

/**
 * @extends TransactionalApplicationService<Request, void>
 */
class TransactionalAtomicallyService extends TransactionalApplicationService implements AtomicallyServiceI
{
    /**
     * @param AtomicallyServiceI         $service
     * @param TransactionalSession<void> $session
     */
    public function __construct(AtomicallyServiceI $service, TransactionalSession $session)
    {
        parent::__construct($service, $session);
    }

    /**
     * {@inheritDoc}
     *
     * @throws TransactionFailedException
     */
    public function execute($request): void
    {
        $this->executeInTransaction($request);
    }
}
