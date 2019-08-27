<?php

namespace DDDStarterPack\Domain\Service;

interface ServiceResponse
{
    public function getSuccess(): bool;

    public function getResponse(): array;
}
