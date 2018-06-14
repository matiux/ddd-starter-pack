<?php

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Application\Exception\ApplicationException;
use DDDStarterPack\Domain\Model\Exception\DomainException;
use DDDStarterPack\Application\Exception\TransactionFailedException;
use DDDStarterPack\Domain\Service\Service;

class TransactionalApplicationService implements ApplicationService
{
    protected $service;
    protected $session;

    public function __construct(Service $service, TransactionalSession $session)
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

        } catch (ApplicationException $exception) {

            throw $exception;


        } catch (DomainException $exception) {

            throw $exception;

        } catch (\Throwable $exception) {

            throw new TransactionFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
