<?php

declare(strict_types=1);

namespace Unit\Entity;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleted;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Acl\UserRole
 */
final class UserRoleTest extends TestCase
{
  /**
   * @group unit
   * @throws UserAlreadyExistsException
   */
  public function testCreate(): void
  {
    $container = $this->createContainer();

    $userRoleCreated = $container->getByType(UserRoleCreated::class);

    // Create dependencies for User
    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $userId = Uuid::uuid4();
    $roleId = Uuid::uuid4();

    // Create a user
    $user = User::create(
      $userId,
      'test@example.com',
      'password123',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a role
    $role = Role::create(Role::POWER_USER);

    // Create a UserRole
    $userRole = UserRole::create($roleId, $user, $role, $userRoleCreated);

    // Assert properties
    self::assertTrue($roleId->equals($userRole->getId()));
    self::assertSame($user, $userRole->getUser());
    self::assertEquals($role->getTitle(), $userRole->getRole()->getTitle());
  }

  /**
   * @group unit
   * @throws UserAlreadyExistsException
   */
  public function testDelete(): void
  {
    $container = $this->createContainer();

    $userRoleCreated = $container->getByType(UserRoleCreated::class);
    $userRoleDeleted = $container->getByType(UserRoleDeleted::class);

    // Create mock for UserRoleDeleted to verify it's called
    $userRoleDeletedMock = $this->createMock(UserRoleDeleted::class);

    // Create dependencies for User
    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $userId = Uuid::uuid4();
    $roleId = Uuid::uuid4();

    // Create a user
    $user = User::create(
      $userId,
      'test-delete@example.com',
      'password123',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a role
    $role = Role::create(Role::POWER_USER);

    // Create a UserRole
    $userRole = UserRole::create($roleId, $user, $role, $userRoleCreated);

    // Set expectation that __invoke is called with the userRole
    $userRoleDeletedMock->expects(self::once())
      ->method('__invoke')
      ->with(self::identicalTo($userRole));

    // Delete the user role
    $result = $userRole->delete($userRoleDeletedMock);

    // Assert that delete method returns the UserRole instance
    self::assertSame($userRole, $result);
  }

  /**
   * @group unit
   * @throws UserAlreadyExistsException
   */
  public function testGetters(): void
  {
    $container = $this->createContainer();

    $userRoleCreated = $container->getByType(UserRoleCreated::class);

    // Create dependencies for User
    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $userId = Uuid::uuid4();
    $roleId = Uuid::uuid4();

    // Create a user
    $user = User::create(
      $userId,
      'test-getters@example.com',
      'password123',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a role
    $role = Role::create(Role::POWER_USER);

    // Create a UserRole
    $userRole = UserRole::create($roleId, $user, $role, $userRoleCreated);

    // Test getId()
    self::assertTrue($roleId->equals($userRole->getId()));

    // Test getUser()
    self::assertSame($user, $userRole->getUser());

    // Test getRole()
    $returnedRole = $userRole->getRole();
    self::assertEquals(Role::POWER_USER, $returnedRole->getTitle());
  }

  /**
   * @group unit
   * @throws UserAlreadyExistsException
   */
  public function testUserRoleCreatedEventIsCalled(): void
  {
    // Create mock for UserRoleCreated to verify it's called
    $userRoleCreatedMock = $this->createMock(UserRoleCreated::class);

    $container = $this->createContainer();

    // Create dependencies for User
    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $userId = Uuid::uuid4();
    $roleId = Uuid::uuid4();

    // Create a user
    $user = User::create(
      $userId,
      'test-event@example.com',
      'password123',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a role
    $role = Role::create(Role::POWER_USER);

    // Set expectation that __invoke is called with the new userRole
    $userRoleCreatedMock->expects(self::once())
      ->method('__invoke')
      ->willReturnCallback(function (UserRole $userRole) use ($user, $roleId) {
        self::assertTrue($roleId->equals($userRole->getId()));
        self::assertSame($user, $userRole->getUser());
        self::assertEquals(Role::POWER_USER, $userRole->getRole()->getTitle());
        return null;
      });

    // Create a UserRole which should trigger the event
    UserRole::create($roleId, $user, $role, $userRoleCreatedMock);
  }
}
