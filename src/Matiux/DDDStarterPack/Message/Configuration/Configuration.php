<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Configuration;

class Configuration
{
    /**
     * @param string                    $driverName
     * @param array<string, int|string> $params
     */
    private function __construct(
        private string $driverName,
        private array $params,
    ) {}

    /**
     * @param string                    $driverName
     * @param array<string, int|string> $params
     *
     * @return Configuration
     */
    public static function withParams(string $driverName, array $params): self
    {
        return new self($driverName, $params);
    }

    public function getDriverName(): string
    {
        return $this->driverName;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
