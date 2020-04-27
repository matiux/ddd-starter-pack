<?php

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationValidator
{
    protected $configurationParamRegistry;
    private $errors = [];

    public function __construct()
    {
        $this->configurationParamRegistry = new ConfigurationParamRegistry;

        $this->buildRegistry();
    }

    abstract protected function buildRegistry();

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

        foreach ($configs as $name => $value) {

            if ($constraint = $this->configurationParamRegistry->resolve($name)) {

                if (!$constraint->isSatisfiedBy($configuration)) {
                    $this->errors[] = $constraint->message();
                }
            }
        }

        return empty($this->errors);
    }
}
