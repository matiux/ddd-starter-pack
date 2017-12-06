<?php

namespace DDDStarterPack\Application\Service;

class TransactionalApplicationService
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

        return $this->session->executeAtomically($operation->bindTo($this));
    }
}
