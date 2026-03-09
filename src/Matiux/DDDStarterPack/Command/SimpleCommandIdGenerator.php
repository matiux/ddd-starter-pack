<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

class SimpleCommandIdGenerator implements CommandIdGenerator
{
    #[\Override]
    public function generate(): CommandId
    {
        return CommandId::new();
    }
}
