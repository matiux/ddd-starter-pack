<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Doctrine\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

abstract class DoctrineRepository
{
    protected $managerRegistry;

    /** @var EntityManager */
    protected $em;

    /** @var string */
    private $entityClassName;

    public function __construct(ManagerRegistry $managerRegistry, string $model)
    {
        $this->managerRegistry = $managerRegistry;
        $this->em = $managerRegistry->getManagerForClass($model);
        $this->entityClassName = $model;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    protected abstract function getEntityAliasName(): string;
}
