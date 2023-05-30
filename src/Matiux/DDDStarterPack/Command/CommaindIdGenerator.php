<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

interface CommaindIdGenerator
{
    public function generate(): CommandId;
}
