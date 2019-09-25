<?php

namespace DDDStarterPack\Domain\Service;

interface DomainServiceResponse
{
    public function getSuccess(): bool;

    public function getResponso();
}
