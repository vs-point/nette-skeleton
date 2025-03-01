<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Locale\Locale;

use Exception;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById
 */
final class LocaleNotFoundByIdTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $id = 'cze';

    $exceptionPrevious = new Exception();

    $exception = new LocaleNotFoundById($id, $exceptionPrevious);

    self::assertStringStartsWith('Locale was not found for id:', $exception->getMessage());
    self::assertSame($id, $exception->getId());
  }
}
