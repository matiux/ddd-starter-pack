<?php

declare(strict_types=1);

namespace DDDStarterPack\Query;

abstract class QueryBus
{
    abstract public function query(Query $query): mixed;

    public function __invoke(Query $query): mixed
    {
        return $this->query($query);
    }
}
