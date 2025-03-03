<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Logic;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Exception\Logic\InvalidStateException;
use VsPoint\Test\TestCase;

#[CoversClass(InvalidStateException::class)]
final class InvalidStateExceptionTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $message = 'exception';

    $prevException = new Exception($message);

    $exception = InvalidStateException::fromPrevious($prevException);

    self::assertSame($message, $exception->getMessage());
  }
}
