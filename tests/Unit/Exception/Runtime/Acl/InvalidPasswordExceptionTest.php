<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use VsPoint\Exception\Runtime\Acl\InvalidPasswordException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\Runtime\Acl\InvalidPasswordException
 */
final class InvalidPasswordExceptionTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $exception = new InvalidPasswordException();

    self::assertSame('Invalid password.', $exception->getMessage());
  }
}
