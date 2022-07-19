<?php

declare(strict_types=1);

namespace Tests\Tool;

use DDDStarterPack\Util\EnvVarUtil;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use Nyholm\Dsn\DsnParser;
use Tests\Support\Model\Doctrine\DoctrineUuidPersonId;

class EntityManagerBuilder
{
    /** @var list<string> */
    private array $paths;

    private bool $isDevMode;

    private Configuration $config;

    /** @var array{dbname: string, driver: string, host: string, password: string, port: int, user: string} */
    private array $connectionParams;

    /** @var EntityManager[] */
    private array $ems = [];

    private function __construct(bool $isDevMode)
    {
        $this->isDevMode = $isDevMode;

        $this->createPaths();
        $this->prepareConnectionParams();

        $this->config = ORMSetup::createXMLMetadataConfiguration($this->paths, $this->isDevMode);
    }

    private function createPaths(): void
    {
        $this->paths = [
            Path::test().'/Support/DbSchema',
        ];
    }

    private function prepareConnectionParams(): void
    {
        $dsn = DsnParser::parse(EnvVarUtil::get('DATABASE_URL'));

        $dbParamsAuth = [
            'driver' => 'pdo_mysql',
            'user' => (string) $dsn->getUser(),
            'password' => (string) $dsn->getPassword(),
            'dbname' => ltrim((string) $dsn->getPath(), '/'),
            'host' => (string) $dsn->getHost(),
            'port' => (int) $dsn->getPort(),
        ];

        $this->connectionParams = $dbParamsAuth;
    }

    public static function create(bool $isDevMode = false): self
    {
        $builder = new self($isDevMode);

        if (!Type::hasType('PersonId')) {
            Type::addType('PersonId', DoctrineUuidPersonId::class);
        }

        $builder->ems['default'] = EntityManager::create($builder->connectionParams, $builder->config);

        return $builder;
    }

    public function getEntityManager(string $em = 'default'): EntityManager
    {
        if (!array_key_exists($em, $this->ems)) {
            throw new InvalidArgumentException('Invalid key for entity manager');
        }

        return $this->ems[$em];
    }

    /**
     * @param class-string $entity
     * @param string       $entityManager
     *
     * @return EntityRepository|ObjectRepository
     */
    public function getRepository(string $entity, string $entityManager = 'default'): EntityRepository|ObjectRepository
    {
        return $this->ems[$entityManager]->getRepository($entity);
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }
}
