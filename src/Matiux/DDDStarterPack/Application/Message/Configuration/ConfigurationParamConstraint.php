<?php

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationParamConstraint
{
    abstract public function isSatisfiedBy(Configuration $configuration): bool;

    public function message(): string
    {
        return sprintf('Invalid %s', $this->name());
    }

    abstract public function name(): string;
}
