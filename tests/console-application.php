<?php

declare(strict_types=1);

use Contributte\Console\Application;
use VsPoint\Bootstrap;

require __DIR__ . '/../vendor/autoload.php';

return Bootstrap::bootForCli()
  ->createContainer()
  ->getByType(Application::class)
;
