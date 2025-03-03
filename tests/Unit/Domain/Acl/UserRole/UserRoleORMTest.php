<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Domain\Acl\UserRole\UserRoleORM;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;

#[CoversClass(UserRoleORM::class)]
final class UserRoleORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $emMock = Mockery::mock(EntityManagerInterface::class);

    $userRoleORM = new UserRoleORM($emMock);
  }

  #[Group('unit')]
  public function testInvoke(): void
  {
    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('persist')->once();

    $userRoleMock = Mockery::mock(UserRole::class);

    $userRoleORM = new UserRoleORM($emMock);
    $userRoleORM->__invoke($userRoleMock);
  }
}
