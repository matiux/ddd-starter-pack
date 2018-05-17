<?php

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Application\Exception\TransactionFailedException;

class TransactionalApplicationService implements ApplicationService
{
    protected $service;
    protected $session;

    public function __construct(ApplicationService $service, TransactionalSession $session)
    {
        $this->service = $service;
        $this->session = $session;
    }

    public function execute($request = null)
    {
        $operation = function () use ($request) {

            return $this->service->execute($request);
        };

        try {

            return $this->session->executeAtomically($operation->bindTo($this));

        } catch (\Throwable $exception) {

            throw new TransactionFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
