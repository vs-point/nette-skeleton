<?php

declare(strict_types=1);

namespace VsPoint\Test;

use Nette\DI\Container;
use PHPUnit\Framework\TestCase as BaseTestCase;
use VsPoint\Bootstrap;

abstract class TestCase extends BaseTestCase
{
  protected function createContainer(): Container
  {
    return Bootstrap::bootForCli()->createContainer();
  }
}
