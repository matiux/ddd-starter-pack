<?php

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
    protected $isBootstrapped = false;

    /** @var ConfigurationValidator */
    protected $configurationValidator;

    public function bootstrap(Configuration $configuration): void
    {
        $this->validateConfiguration($configuration);

        $this->setSpecificConfiguration($configuration);

        $this->isBootstrapped = true;
    }

    private function validateConfiguration(Configuration $configuration): void
    {
        $this->validateDriverName($configuration);
        $this->checkRequiredParams($configuration);

        if (!$this->checkParamsIsValid($configuration)) {
            $errors = $this->configurationValidator->errors();
            throw new InvalidConfigurationException(sprintf('%s', implode("|", $errors)));
        }
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

        } catch (MissingOptionsException | Throwable $e) {

            throw new InvalidConfigurationException($e->getMessage());
        }
    }

    protected abstract function requiredParams(): array;

    protected abstract function defaultsParams(): array;

    abstract protected function checkParamsIsValid(Configuration $configuration): bool;

    abstract protected function setSpecificConfiguration(Configuration $configuration): void;

    public function name(): string
    {
        return $this->specificDriverName();
    }
}
