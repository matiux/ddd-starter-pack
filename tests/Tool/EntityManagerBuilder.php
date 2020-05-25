<?php

declare(strict_types=1);

namespace Tests\Tool;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Tests\Support\Model\Doctrine\DoctrinePersonId;

class EntityManagerBuilder
{
    /** @var list<string> */
    private $paths;

    /** @var bool */
    private $isDevMode;

    /** @var Configuration */
    private $config;

    /** @var array{dbname: string, driver: string, host: string, password: string, port: int, user: string} */
    private $connectionParams;

    /** @var EntityManager[] */
    private $ems = [];

    private function __construct(bool $isDevMode)
    {
        $this->isDevMode = $isDevMode;

        $this->createPaths();
        $this->prepareConnectionParams();

        $this->config = Setup::createXMLMetadataConfiguration($this->paths, $this->isDevMode);
    }

    private function createPaths(): void
    {
        $this->paths = [
            Path::test().'/Support/DbSchema',
        ];
    }

    private function prepareConnectionParams(): void
    {
        $dbParamsAuth = [
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'ddd_sp_test',
            'host' => 'servicedb',
            'port' => 3306,
        ];

        $this->connectionParams = $dbParamsAuth;
    }

    public static function create(bool $isDevMode = false): self
    {
        $builder = new self($isDevMode);

        if (!Type::hasType('PersonId')) {
            Type::addType('PersonId', DoctrinePersonId::class);
        }

        $builder->ems['default'] = EntityManager::create($builder->connectionParams, $builder->config);

        return $builder;
    }

    public function getEntityManager(string $em = 'default'): EntityManager
    {
        if (!array_key_exists($em, $this->ems)) {
            throw new \InvalidArgumentException('Invalid key for entity manager');
        }

        return $this->ems[$em];
    }

    /**
     * @param class-string $entity
     * @param string       $entityManager
     *
     * @return \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    public function getRepository(string $entity, string $entityManager = 'default')
    {
        return $this->ems[$entityManager]->getRepository($entity);
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }
}
