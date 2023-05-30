<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

class SimpleCommandIdGenerator implements CommaindIdGenerator
{
    public function generate(): CommandId
    {
        return CommandId::new();
    }
}
