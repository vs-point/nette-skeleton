<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleteORM;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\UserRole\UserRoleDeleteORM
 */
final class UserRoleDeleteORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $emMock = Mockery::mock(EntityManagerInterface::class);

    $userRoleDeleteORM = new UserRoleDeleteORM($emMock);
  }

  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('remove')->once();

    $userRoleMock = Mockery::mock(UserRole::class);

    $userRoleORM = new UserRoleDeleteORM($emMock);
    $userRoleORM->__invoke($userRoleMock);
  }
}
