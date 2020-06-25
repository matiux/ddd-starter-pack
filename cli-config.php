<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Tests\Tool\EntityManagerBuilder;

$em = EntityManagerBuilder::create(true)->getEntityManager('default');

return ConsoleRunner::createHelperSet($em);
