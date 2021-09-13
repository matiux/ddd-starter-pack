<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Configuration;

use RuntimeException;

class ConfigurationParamRegistry
{
    /** @var ConfigurationParamConstraint[] */
    private array $configConstraints = [];

    public function addConstraint(ConfigurationParamConstraint $configurationParamConstraint): void
    {
        $configConstraintName = $configurationParamConstraint->name();

        if (isset($this->configConstraints[$configConstraintName])) {
            throw new RuntimeException(sprintf('Constraint %s is already set', $configConstraintName));
        }

        $this->configConstraints[$configConstraintName] = $configurationParamConstraint;
    }

    public function resolve(string $configurationParamConstraintName): ?ConfigurationParamConstraint
    {
        return $this->configConstraints[$configurationParamConstraintName] ?? null;
    }
}
