<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter\Test;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class FilterApplierScenarioTestCase extends KernelTestCase
{
    protected FilterApplierScenario $scenario;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        /**
         * @psalm-suppress PropertyTypeCoercion
         */
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
        $this->createScenario();
    }

    private function createScenario(): void
    {
        $this->scenario = new FilterApplierScenario(
            $this,
            $this->createFilterParamBuilder(),
            $this->createQueryBuilder(),
        );
    }

    abstract protected function createFilterParamBuilder(): FilterBuilder;

    abstract protected function createQueryBuilder(): QueryBuilder;
}
