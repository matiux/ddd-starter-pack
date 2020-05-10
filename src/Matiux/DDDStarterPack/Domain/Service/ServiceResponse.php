<?php

namespace DDDStarterPack\Domain\Service;

interface ServiceResponse
{
    public function isSuccess(): bool;

    public function body();

    public function code(): int;
}
