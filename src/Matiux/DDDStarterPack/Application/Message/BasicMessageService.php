<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;
use DDDStarterPack\Application\Message\Exception\InvalidConfigurationException;
use InvalidArgumentException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

abstract class BasicMessageService
{
    protected bool $isBootstrapped = false;

    protected ConfigurationValidator $configurationValidator;

    public function __construct()
    {
        $this->configurationValidator = $this->obtainConfigurationValidator();
    }

    public function bootstrap(Configuration $configuration): void
    {
        $this->validateConfiguration($configuration);

        $this->setSpecificConfiguration($configuration);

        $this->isBootstrapped = true;
    }

    public function name(): string
    {
        return $this->specificDriverName();
    }

    protected function validateDriverName(Configuration $configuration): void
    {
        if ($configuration->getDriverName() !== $this->specificDriverName()) {
            throw new InvalidArgumentException(sprintf("Configuration driver name '%s' not match with specific name '%s'", $configuration->getDriverName(), $this->specificDriverName()));
        }
    }

    abstract protected function specificDriverName(): string;

    protected function checkRequiredParams(Configuration $configuration): void
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired($this->requiredParams());

        if (!empty($this->defaultsParams())) {
            $resolver->setDefaults($this->defaultsParams());
        }

        try {
            $resolver->resolve($configuration->getParams());
        } catch (MissingOptionsException|Throwable $e) {
            throw new InvalidConfigurationException($e->getMessage());
        }
    }

    /**
     * @psalm-return list<string>
     */
    abstract protected function requiredParams(): array;

    abstract protected function defaultsParams(): array;

    abstract protected function obtainConfigurationValidator(): ConfigurationValidator;

    abstract protected function setSpecificConfiguration(Configuration $configuration): void;

    private function validateConfiguration(Configuration $configuration): void
    {
        $this->validateDriverName($configuration);
        $this->checkRequiredParams($configuration);

        if (!$this->configurationValidator->validate($configuration)) {
            $errors = $this->configurationValidator->errors();

            throw new InvalidConfigurationException(sprintf('%s', implode('|', $errors)));
        }
    }
}
