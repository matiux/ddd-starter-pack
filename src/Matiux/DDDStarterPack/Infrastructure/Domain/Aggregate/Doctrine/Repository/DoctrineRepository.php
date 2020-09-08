<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

abstract class DoctrineRepository
{
    /** @var Registry */
    protected $registry;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var string */
    private $entityClassName;

    public function __construct(Registry $registry, string $model)
    {
        $this->registry = $registry;

        if (!$em = $registry->getManagerForClass($model)) {
            throw new LogicException('Entity manager cannot be null');
        }
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->em = $em;
        $this->entityClassName = $model;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    abstract protected function getEntityAliasName(): string;
}
