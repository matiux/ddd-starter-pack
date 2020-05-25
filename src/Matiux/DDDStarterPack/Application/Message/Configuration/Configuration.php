<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Configuration;

class Configuration
{
    /** @var string string */
    private $driverName;

    /** @var array<string, int|string> */
    private $params;

    /**
     * @param string                    $driverName
     * @param array<string, int|string> $params
     */
    private function __construct(string $driverName, array $params)
    {
        $this->driverName = $driverName;
        $this->params = $params;
    }

    /**
     * @param string                    $driverName
     * @param array<string, int|string> $params
     */
    public static function withParams(string $driverName, array $params): self
    {
        return new self($driverName, $params);
    }

    public function getDriverName(): string
    {
        return $this->driverName;
    }

    /**
     * @return array<array-key, int|string|null|bool>
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
