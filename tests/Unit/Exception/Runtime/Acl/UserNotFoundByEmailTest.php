<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Exception;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Test\TestCase;

#[CoversClass(UserNotFoundByEmail::class)]
final class UserNotFoundByEmailTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $email = 'test@email.com';

    $exceptionPrevious = new Exception();

    $exception = new UserNotFoundByEmail($email, $exceptionPrevious);

    self::assertStringStartsWith('User was not found for email:', $exception->getMessage());
    self::assertSame($email, $exception->getEmail());
  }
}
