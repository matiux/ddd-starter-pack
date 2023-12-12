<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Command;

use DDDStarterPack\Command\Command;
use DDDStarterPack\Command\CommandId;
use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_command_name_internally(): void
    {
        $commandId = CommandId::new();

        $command = new CreateUserCommand(
            $commandId,
            AggregateId::new(),
            DomainTrace::init($commandId),
            new DateTimeRFC(),
        );

        self::assertEquals('create_user_command', $command->commandName);
    }
}

/**
 * @extends Command<AggregateId>
 */
readonly class CreateUserCommand extends Command {}
