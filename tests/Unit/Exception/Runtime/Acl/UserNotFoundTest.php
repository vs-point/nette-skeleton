<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Test\TestCase;

#[CoversClass(UserNotFound::class)]
final class UserNotFoundTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $uuid = Uuid::uuid4();

    $exception = new UserNotFound($uuid);

    self::assertStringStartsWith('User was not found for id:', $exception->getMessage());
    self::assertSame($uuid, $exception->getId());
  }
}
