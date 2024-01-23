<?php

declare(strict_types=1);

namespace DDDStarterPack\Service;

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
        return $this->session->executeAtomically(
            fn () => $this->service->execute($request),
        );
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
