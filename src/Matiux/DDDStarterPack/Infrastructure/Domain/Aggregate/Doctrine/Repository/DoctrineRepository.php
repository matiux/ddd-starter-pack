<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

abstract class DoctrineRepository
{
    /** @var ManagerRegistry */
    protected $managerRegistry;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var string */
    private $entityClassName;

    public function __construct(ManagerRegistry $managerRegistry, string $model)
    {
        $this->managerRegistry = $managerRegistry;

        if (!$em = $managerRegistry->getManagerForClass($model)) {
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
