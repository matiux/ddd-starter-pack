<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Configuration;

abstract class ConfigurationParamConstraint
{
    public function isSatisfiedBy(Configuration $configuration): bool
    {
        $configs = $configuration->getParams();

        if (array_key_exists($this->name(), $configs)) {
            $_param = $configs[$this->name()];
            $_param = is_string($_param) ? trim($_param) : $_param;
        }

        return !empty($_param);
    }

    public function message(): string
    {
        return sprintf('Invalid %s', $this->name());
    }

    abstract public function name(): string;
}
