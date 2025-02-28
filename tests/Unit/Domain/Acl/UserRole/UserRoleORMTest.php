<?php

declare(strict_types=1);

namespace Unit\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Domain\Acl\UserRole\UserRoleORM;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\UserRole\UserRoleORM
 */
final class UserRoleORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $emMock = Mockery::mock(EntityManagerInterface::class);

    $userRoleORM = new UserRoleORM($emMock);
  }

  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('persist')->once();

    $userRoleMock = Mockery::mock(UserRole::class);

    $userRoleORM = new UserRoleORM($emMock);
    $userRoleORM->__invoke($userRoleMock);
  }
}
