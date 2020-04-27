<?php

namespace DDDStarterPack\Application\Message\Configuration;

class Configuration
{
    private $driverName;
    private $params;

    private function __construct(string $driverName, array $params)
    {
        $this->driverName = $driverName;
        $this->params = $params;
    }

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
