<?php

declare(strict_types=1);

use Doctrine\Persistence\ObjectManager;
use VsPoint\Bootstrap;

require __DIR__ . '/../vendor/autoload.php';

return Bootstrap::bootForCli()
  ->createContainer()
  ->getByType(ObjectManager::class)
;
