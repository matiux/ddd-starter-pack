<?php

declare(strict_types=1);

namespace DDDStarterPack\Query;

interface QueryBus
{
    public function query(Query $query): mixed;
}
