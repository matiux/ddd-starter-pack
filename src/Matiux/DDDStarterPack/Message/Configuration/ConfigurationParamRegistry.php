<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Configuration;

class ConfigurationParamRegistry
{
    /** @var ConfigurationParamConstraint[] */
    private array $configConstraints = [];

    public function addConstraint(ConfigurationParamConstraint $configurationParamConstraint): void
    {
        $configConstraintName = $configurationParamConstraint->name();

        if (isset($this->configConstraints[$configConstraintName])) {
            throw new \RuntimeException(sprintf('Constraint %s is already set', $configConstraintName));
        }

        $this->configConstraints[$configConstraintName] = $configurationParamConstraint;
    }

    public function resolve(string $configurationParamConstraintName): null|ConfigurationParamConstraint
    {
        return $this->configConstraints[$configurationParamConstraintName] ?? null;
    }
}
