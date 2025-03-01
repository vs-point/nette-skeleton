<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Exception\Runtime\Acl\InvalidPasswordException;
use VsPoint\Test\TestCase;

#[CoversClass(InvalidPasswordException::class)]
final class InvalidPasswordExceptionTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $exception = new InvalidPasswordException();

    self::assertSame('Invalid password.', $exception->getMessage());
  }
}
