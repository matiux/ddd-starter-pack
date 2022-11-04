<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

abstract class DoctrineRepository
{
    protected Registry $registry;
    protected EntityManagerInterface $em;

    /**
     * @var class-string
     */
    private string $entityClassName;

    /**
     * @psalm-assert class-string $model
     *
     * @param Registry     $registry
     * @param class-string $model
     */
    public function __construct(Registry $registry, string $model)
    {
        if (!class_exists($model)) {
            throw new \InvalidArgumentException(sprintf('`%s` is not a valid class-string', (string) $model));
        }

        $this->registry = $registry;

        $em = $registry->getManagerForClass($model);

        Assert::notNull($em, 'Entity manager cannot be null');

        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->em = $em;
        $this->entityClassName = $model;
    }

    /**
     * @return class-string
     */
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    abstract protected function getEntityAliasName(): string;
}
