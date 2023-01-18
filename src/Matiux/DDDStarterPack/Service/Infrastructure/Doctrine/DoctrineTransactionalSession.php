<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Infrastructure\Doctrine;

use DDDStarterPack\Exception\Application\TransactionFailedException;
use DDDStarterPack\Service\Application\TransactionalSession;
use Doctrine\ORM\EntityManager;

/**
 * @template O
 *
 * @implements TransactionalSession<O>
 */
class DoctrineTransactionalSession implements TransactionalSession
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function executeAtomically(callable $operation)
    {
        try {
            /** @var O $reponse */
            $reponse = $this->entityManager->wrapInTransaction($operation);

            return $reponse;
        } catch (\Throwable $t) {
            throw new TransactionFailedException(sprintf('Transaction failed: %s', $t->getMessage()), (int) $t->getCode(), $t);
        }
    }
}
