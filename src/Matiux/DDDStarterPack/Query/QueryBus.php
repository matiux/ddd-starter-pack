<?php

namespace DDDStarterPack\Query;

interface QueryBus
{
    public function query(Query $query): mixed;
}