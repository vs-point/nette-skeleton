<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail
 */
final class UserNotFoundByEmailTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $email = 'test@email.com';

    $exceptionPrevious = new \Exception();

    $exception = new UserNotFoundByEmail($email, $exceptionPrevious);

    self::assertStringStartsWith('User was not found for email:', $exception->getMessage());
    self::assertSame($email, $exception->getEmail());
  }
}
