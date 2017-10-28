<?php

namespace DddStarterPack\Domain\Model;

interface IdentifiableDomainObject
{
    public function id(): EntityId;
}
