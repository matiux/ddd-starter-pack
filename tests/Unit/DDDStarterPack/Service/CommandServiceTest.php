<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Service;

use DDDStarterPack\Command\Command;
use DDDStarterPack\Command\CommandId;
use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Service\CommandService;
use DDDStarterPack\Type\DateTimeRFC;
use PHPUnit\Framework\TestCase;

class CommandServiceTest extends TestCase
{
    /**
     * @test
     */
    public function service_requires_a_domain_command(): void
    {
        $store = new Store();

        $service = new MyCommandService($store);

        // Psalm gets angry - as it should be
        // $service->execute();
        // $service->execute(2);
        // $service->execute([]);

        $service->execute(MyCommand::init());

        self::assertSame('foo-bar', $store->getLog());
    }
}

/**
 * @extends Command<AggregateId>
 */
readonly class MyCommand extends Command
{
    public function __construct(
        CommandId $commandId,
        AggregateId $aggregateId,
        DomainTrace $domainTrace,
        DateTimeRFC $occurredAt,
        public string $data,
    ) {
        parent::__construct($commandId, $aggregateId, $domainTrace, $occurredAt);
    }

    public static function init(): self
    {
        $commandId = CommandId::new();

        return new self(
            $commandId,
            AggregateId::new(),
            DomainTrace::init($commandId),
            new DateTimeRFC(),
            'foo-bar',
        );
    }
}

/**
 * @implements CommandService<MyCommand>
 */
class MyCommandService implements CommandService
{
    public function __construct(
        private Store $store,
    ) {}

    /**
     * @param MyCommand $command
     */
    public function execute($command): void
    {
        $this->store->log($command->data);
    }
}

class Store
{
    private string $log = '';

    public function log(string $log): void
    {
        $this->log = $log;
    }

    public function getLog(): string
    {
        return $this->log;
    }
}
