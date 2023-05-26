<?php

declare(strict_types=1);

namespace Tests\Support\Model\Doctrine;

use DDDStarterPack\Repository\Doctrine\Identifier\DoctrineUuidEntityId;
use Tests\Support\Model\PersonId;

class DoctrineUuidPersonId extends DoctrineUuidEntityId
{
    public function getName()
    {
        return 'PersonId';
    }

    protected function getFQCN(): string
    {
        return PersonId::class;
    }
}
