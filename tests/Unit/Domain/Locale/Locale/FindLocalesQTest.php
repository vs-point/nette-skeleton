<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Locale\Locale;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Domain\Locale\Locale\FindLocalesQ;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Locale\Locale\FindLocalesQ
 */
class FindLocalesQTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testGet(): void
  {
    $container = $this->createContainer();

    $findLocalesQ = $container->getByType(FindLocalesQ::class);
    $locales = $findLocalesQ->__invoke(10, 0);

    self::assertCount(2, $locales);
    self::assertContainsOnlyInstancesOf(Locale::class, $locales);
  }
}
