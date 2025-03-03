<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Locale\Locale;

use Exception;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;
use VsPoint\Test\TestCase;

#[CoversClass(LocaleNotFoundById::class)]
final class LocaleNotFoundByIdTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $id = 'cze';

    $exceptionPrevious = new Exception();

    $exception = new LocaleNotFoundById($id, $exceptionPrevious);

    self::assertStringStartsWith('Locale was not found for id:', $exception->getMessage());
    self::assertSame($id, $exception->getId());
  }
}
