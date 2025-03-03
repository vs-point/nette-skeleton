<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleteORM;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;

#[CoversClass(UserRoleDeleteORM::class)]
final class UserRoleDeleteORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $emMock = Mockery::mock(EntityManagerInterface::class);

    $userRoleDeleteORM = new UserRoleDeleteORM($emMock);
  }

  #[Group('unit')]
  public function testInvoke(): void
  {
    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('remove')->once();

    $userRoleMock = Mockery::mock(UserRole::class);

    $userRoleORM = new UserRoleDeleteORM($emMock);
    $userRoleORM->__invoke($userRoleMock);
  }
}
