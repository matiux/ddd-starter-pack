<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

interface CommandIdGenerator
{
    public function generate(): CommandId;
}
