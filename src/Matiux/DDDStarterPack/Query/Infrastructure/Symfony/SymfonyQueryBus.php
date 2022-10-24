<?php

namespace DDDStarterPack\Query\Infrastructure\Symfony;

use DDDStarterPack\Query\Query;
use DDDStarterPack\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
class SymfonyQueryBus implements QueryBus
{
    use HandleTrait;

     public function __construct(MessageBusInterface $queryBus)
      {
          $this->messageBus = $queryBus;
      }

    public function query(Query $query): mixed
    {
        try {
            return $this->handle($query);
        }  catch (HandlerFailedException $e) {
            throw first($e->getNestedExceptions());
        }
    }
}