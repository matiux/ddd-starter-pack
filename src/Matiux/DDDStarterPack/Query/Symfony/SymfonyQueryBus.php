<?php

declare(strict_types=1);

namespace DDDStarterPack\Query\Symfony;

use DDDStarterPack\Query\Query;
use DDDStarterPack\Query\QueryBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

class SymfonyQueryBus extends QueryBus
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
        } catch (HandlerFailedException $e) {
            /**
             * SF7 uses getWrappedExceptions() instead getNestedExceptions().
             *
             * @psalm-suppress UndefinedMethod
             *
             * @var \Throwable[] $es
             */
            $es = method_exists($e, 'getWrappedExceptions') ? $e->getWrappedExceptions() : $e->getNestedExceptions();

            $first = current($es);
            Assert::isInstanceOf($first, \Throwable::class);

            throw $first;
        }
    }
}
