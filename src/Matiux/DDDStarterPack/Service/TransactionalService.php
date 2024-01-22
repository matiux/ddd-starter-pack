<?php

declare(strict_types=1);

namespace DDDStarterPack\Service;

use DDDStarterPack\Exception\DomainException;
use DDDStarterPack\Exception\TransactionFailedException;

/**
 * @template I
 * @template O
 *
 * @implements Service<I, O>
 */
class TransactionalService implements Service
{
    /**
     * @param Service<I, O>           $service
     * @param TransactionalSession<O> $session
     */
    public function __construct(
        protected Service $service,
        protected TransactionalSession $session,
    ) {}

    /**
     * @param I $request
     *
     * @throws TransactionFailedException
     *
     * @return O
     */
    public function execute($request = null)
    {
        try {
            return $this->session->executeAtomically(function () use ($request) {
                return $this->service->execute($request);
            });
        } catch (\Throwable $exception) {
            $context = $exception instanceof DomainException ? $exception->getContext() : [];

            throw TransactionFailedException::fromOriginalException($exception, $context);
        }
    }

    /**
     * @param I $request
     *
     * @throws TransactionFailedException
     *
     * @return O
     */
    public function __invoke($request = null)
    {
        return $this->execute($request);
    }
}
