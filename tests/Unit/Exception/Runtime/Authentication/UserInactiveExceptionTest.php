<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Runtime\Authentication;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Authentication\UserInactiveException;
use VsPoint\Test\TestCase;

#[CoversClass(UserInactiveException::class)]
final class UserInactiveExceptionTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $userMock = Mockery::mock(User::class);

    $exception = new UserInactiveException($userMock);

    self::assertSame('User inactive.', $exception->getMessage());
    self::assertSame($userMock, $exception->getUser());
  }
}
