<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationParamConstraint
{
    public function isSatisfiedBy(Configuration $configuration): bool
    {
        $configs = $configuration->getParams();

        if (array_key_exists($this->name(), $configs)) {
            $param = $configs[$this->name()];
            $param = is_string($param) ? trim($param) : $param;
        }

        return !empty($param);
    }

    public function message(): string
    {
        return sprintf('Invalid %s', $this->name());
    }

    abstract public function name(): string;
}
