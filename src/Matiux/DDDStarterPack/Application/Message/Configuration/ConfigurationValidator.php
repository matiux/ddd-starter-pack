<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationValidator
{
    /** @var ConfigurationParamRegistry */
    protected $configurationParamRegistry;

    /** @var array */
    private $errors = [];

    public function __construct()
    {
        $this->configurationParamRegistry = new ConfigurationParamRegistry();

        $this->buildRegistry();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validate(Configuration $configuration): bool
    {
        $configs = $configuration->getParams();

        if (empty($configs) || (1 === count($configs) && isset($configs['cache']))) {
            $this->errors[] = 'Empty configs';

            return false;
        }

        foreach ($configs as $name => $_value) {
            $configurationParamConstraintName = (string) $name;

            if ($constraint = $this->configurationParamRegistry->resolve($configurationParamConstraintName)) {
                if (!$constraint->isSatisfiedBy($configuration)) {
                    $this->errors[] = $constraint->message();
                }
            }
        }

        return empty($this->errors);
    }

    abstract protected function buildRegistry(): void;
}
