<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Service\Domain;

use DateTimeImmutable;
use DDDStarterPack\Command\DomainCommand;
use DDDStarterPack\Service\Domain\CommandService;
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

        $service->execute(new MyCommand());

        self::assertSame('operation', $store->getLog());
    }
}

class MyCommand implements DomainCommand
{
    public function occurredAt(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function operation(): string
    {
        return 'operation';
    }
}

/**
 * @implements CommandService<MyCommand>
 */
class MyCommandService implements CommandService
{
    public function __construct(
        private Store $store,
    ) {
    }

    /**
     * @param MyCommand $request
     */
    public function execute($request): void
    {
        $this->store->log($request->operation());
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
