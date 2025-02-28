<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Logic;

use Exception;
use VsPoint\Exception\Logic\InvalidStateException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\Logic\InvalidStateException
 */
final class InvalidStateExceptionTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $message = 'exception';

    $prevException = new Exception($message);

    $exception = InvalidStateException::fromPrevious($prevException);

    self::assertSame($message, $exception->getMessage());
  }
}
