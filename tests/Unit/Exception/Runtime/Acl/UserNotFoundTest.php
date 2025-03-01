<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use Ramsey\Uuid\Uuid;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\Runtime\Acl\UserNotFound
 */
final class UserNotFoundTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $uuid = Uuid::uuid4();

    $exception = new UserNotFound($uuid);

    self::assertStringStartsWith('User was not found for id:', $exception->getMessage());
    self::assertSame($uuid, $exception->getId());
  }
}
