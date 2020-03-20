<?php

namespace DDDStarterPack\Domain\Service;

interface ServiceResponse
{
    public function isSuccess(): bool;

    public function message(): string;

    public function code(): int;
}
