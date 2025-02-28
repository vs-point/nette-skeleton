<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserByIdQ;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleteORM;
use VsPoint\Domain\Acl\UserRole\UserRoleORM;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\UserRole\UserRoleDeleteORM
 */
final class UserRoleDeleteORMTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleORM = new UserRoleDeleteORM($em);

    self::assertInstanceOf(UserRoleDeleteORM::class, $userRoleORM);
  }

  /**
   * @group unit
   * @throws UserNotFound
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();
    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleDeleteORM = new UserRoleDeleteORM($em);
    $userRoleORM = new UserRoleORM($em);

    $getUserByIdQ = $container->getByType(GetUserByIdQ::class);
    $userId = Uuid::fromString(InitFixture::USER_01);
    $user = $getUserByIdQ->__invoke($userId);

    $userRoleUuid = Uuid::fromString(InitFixture::USER_ROLE_POWER_USER);
    $role = Role::create(Role::POWER_USER);

    $userRole = UserRole::create($userRoleUuid, $user, $role, $userRoleORM);

    $userRoleDeleteORM->__invoke($userRole);

    self::assertEquals($userRoleUuid, $userRole->getId());
  }
}
