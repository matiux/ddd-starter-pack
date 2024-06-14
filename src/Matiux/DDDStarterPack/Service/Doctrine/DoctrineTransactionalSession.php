<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Doctrine;

use DDDStarterPack\Exception\TransactionFailedException;
use DDDStarterPack\Service\TransactionalSession;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template O
 *
 * @implements TransactionalSession<O>
 */
readonly class DoctrineTransactionalSession implements TransactionalSession
{
    public function __construct(
        private EntityManager $entityManager,
        private ManagerRegistry $managerRegistry,
    ) {}

    /** {@inheritDoc} */
    public function executeAtomically(callable $operation)
    {
        try {
            /** @var O $response */
            $response = $this->entityManager->wrapInTransaction($operation);

            return $response;
        } catch (\Throwable $exception) {
            $this->managerRegistry->resetManager();

            throw TransactionFailedException::fromOriginalException($exception);
        }
    }
}
