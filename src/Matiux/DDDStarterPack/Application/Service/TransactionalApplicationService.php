<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Application\Exception\TransactionFailedException;
use DDDStarterPack\Domain\Service\Service;
use Throwable;

/**
 * @template I
 * @template O
 */
abstract class TransactionalApplicationService
{
    /**
     * @param Service<I, O>           $service
     * @param TransactionalSession<O> $session
     */
    public function __construct(
        protected Service $service,
        protected TransactionalSession $session,
    ) {
    }

    /**
     * @param I $request
     *
     * @throws TransactionFailedException
     *
     * @return O
     */
    public function executeInTransaction($request = null)
    {
        try {
            return $this->session->executeAtomically(function () use ($request) {
                return $this->service->execute($request);
            });
        } catch (Throwable $exception) {
            throw new TransactionFailedException($exception->getMessage(), intval($exception->getCode()), $exception);
        }
    }
}
