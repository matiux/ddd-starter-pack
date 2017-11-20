<?php

namespace DddStarterPack\Domain\Model;

interface IdentifiableDomainObject
{
    /**
     * @return EntityId
     */
    public function id();
}
