<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Entity;

use Nette\Security\Passwords;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\User\UserEdited;
use VsPoint\Domain\Acl\User\UserLoggedIn;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleted;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Exception\Runtime\Authentication\InvalidPasswordException;
use VsPoint\Exception\Runtime\Authentication\UserInactiveException;
use VsPoint\Test\TestCase;

#[CoversClass(User::class)]
final class UserTest extends TestCase
{
  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testCreate(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $pass = 'MFD_mpb3vjw8wcb.tvqa';
    $userEmail = 'test@email.com';

    $user = User::create(
      $uuid,
      $userEmail,
      $pass,
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    self::assertSame($userEmail, $user->getEmail());
    self::assertTrue($passwords->verify($pass, $user->getPassword()));
    self::assertEquals($now, $user->getCreatedAt());
    self::assertTrue($uuid->equals($user->getId()));
    self::assertEquals($now, $user->getGdpr());
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testGetters(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $password = 'TestPassword123';
    $email = 'test@example.com';
    $expiration = $now->plusDays(30);
    $gdpr = $now->plusDays(5);

    $user = User::create(
      $uuid,
      $email,
      $password,
      $expiration,
      $now,
      $gdpr,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Test all getters
    self::assertSame($email, $user->getEmail());
    self::assertTrue($passwords->verify($password, $user->getPassword()));
    self::assertEquals($expiration, $user->getExpiration());
    self::assertEquals($now, $user->getCreatedAt());
    self::assertEquals($gdpr, $user->getGdpr());
    self::assertTrue($uuid->equals($user->getId()));

    // Test active user
    self::assertTrue($user->isActive($now));
    self::assertTrue($user->isActive($now->plusDays(29)));
    self::assertFalse($user->isActive($now->plusDays(31)));

    // Test user with null expiration
    $userNoExpiration = User::create(
      Uuid::uuid4(),
      'no-expiration@example.com',
      $password,
      null,
      $now,
      $gdpr,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // User with null expiration is always active
    self::assertTrue($userNoExpiration->isActive($now));
    self::assertTrue($userNoExpiration->isActive($now->plusYears(10)));

    // Test password verification helper method
    self::assertTrue($user->isPasswordCorrect($password, $passwords));
    self::assertFalse($user->isPasswordCorrect('wrong-password', $passwords));
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testRolesGetters(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);
    $userRoleCreated = $container->getByType(UserRoleCreated::class);
    $userRoleDeleted = $container->getByType(UserRoleDeleted::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $email = 'roles-test@example.com';

    $user = User::create(
      $uuid,
      $email,
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // By default, a new user should only have the 'user' role
    self::assertEquals(['user'], $user->getRoles());
    self::assertEquals([], $user->getUserRolesList());

    // Add roles to the user
    $user->editUserRoles([Role::POWER_USER], $userRoleCreated, $userRoleDeleted);

    // Test getRoles() includes 'user' and roles from UserRole entities
    self::assertEquals(['user', Role::POWER_USER], $user->getRoles());
    self::assertEquals([Role::POWER_USER], $user->getUserRolesList());

    // Test getUserRoles() returns a Sequence of UserRole objects
    $userRoles = $user->getUserRoles();
    self::assertCount(1, $userRoles);
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testEdit(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userEdited = $container->getByType(UserEdited::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $user = User::create(
      $uuid,
      'initial@example.com',
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    $newEmail = 'updated@example.com';
    $newExpiration = $now->plusDays(60);

    $user->edit($newEmail, $newExpiration, $doesUserExist, $userEdited);

    self::assertSame($newEmail, $user->getEmail());
    self::assertEquals($newExpiration, $user->getExpiration());
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testEditPassword(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userEdited = $container->getByType(UserEdited::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $initialPassword = 'initial-password';

    $user = User::create(
      $uuid,
      'password-test@example.com',
      $initialPassword,
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Verify initial password is correct
    self::assertTrue($passwords->verify($initialPassword, $user->getPassword()));

    // Change the password
    $newPassword = 'new-secure-password123';
    $user->editPassword($newPassword, $passwords, $userEdited);

    // Verify new password works and old one doesn't
    self::assertTrue($passwords->verify($newPassword, $user->getPassword()));
    self::assertFalse($passwords->verify($initialPassword, $user->getPassword()));
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testEditUserRolesRemovesOldRoles(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);
    $userRoleCreated = $container->getByType(UserRoleCreated::class);
    $userRoleDeleted = $container->getByType(UserRoleDeleted::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();

    $user = User::create(
      $uuid,
      'edit-roles@example.com',
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Add initial roles
    $user->editUserRoles([Role::POWER_USER], $userRoleCreated, $userRoleDeleted);
    self::assertEquals([Role::POWER_USER], $user->getUserRolesList());

    // Change to different roles
    $user->editUserRoles([Role::POWER_USER], $userRoleCreated, $userRoleDeleted);

    // Verify old roles are removed and new ones added
    self::assertEquals([Role::POWER_USER], $user->getUserRolesList());
    self::assertEquals(['user', Role::POWER_USER], $user->getRoles());
  }

  /**
   * @throws UserAlreadyExistsException
   * @throws InvalidPasswordException
   * @throws UserInactiveException
   */
  #[Group('unit')]
  public function testLogIn(): void
  {
    $this->expectNotToPerformAssertions();

    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userLoggedIn = $container->getByType(UserLoggedIn::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $password = 'login-test-password';

    $user = User::create(
      $uuid,
      'login-test@example.com',
      $password,
      $now->plusDays(30),
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Successful login
    $user->logIn($passwords, $password, $now, $userLoggedIn);
  }

  /**
   * @throws UserAlreadyExistsException
   * @throws UserInactiveException
   * @throws InvalidPasswordException
   */
  #[Group('unit')]
  public function testLogInInvalidPassword(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userLoggedIn = $container->getByType(UserLoggedIn::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $password = 'correct-password';

    $user = User::create(
      $uuid,
      'invalid-pass@example.com',
      $password,
      $now->plusDays(30),
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Expect exception for wrong password
    $this->expectException(InvalidPasswordException::class);
    $user->logIn($passwords, 'wrong-password', $now, $userLoggedIn);
  }

  /**
   * @throws UserAlreadyExistsException
   * @throws UserInactiveException
   * @throws InvalidPasswordException
   */
  #[Group('unit')]
  public function testLogInInactiveUser(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userLoggedIn = $container->getByType(UserLoggedIn::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $password = 'correct-password';

    // Create user with expiration in the past
    $user = User::create(
      $uuid,
      'expired-user@example.com',
      $password,
      $now->minusDays(1), // Expired
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Expect exception for inactive user
    $this->expectException(UserInactiveException::class);
    $user->logIn($passwords, $password, $now, $userLoggedIn);
  }

  /**
   * @throws UserAlreadyExistsException
   * @throws UserInactiveException
   * @throws InvalidPasswordException
   */
  #[Group('unit')]
  public function testLogInCallsUserLoggedInEvent(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $password = 'login-event-test-password';

    $user = User::create(
      $uuid,
      'login-event-test@example.com',
      $password,
      $now->plusDays(30),
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a mock for UserLoggedIn
    $userLoggedIn = $this->createMock(UserLoggedIn::class);

    // Set expectation that __invoke is called with the user
    $userLoggedIn->expects(self::once())
      ->method('__invoke')
      ->with(self::identicalTo($user));

    // Perform login which should trigger the event
    $user->logIn($passwords, $password, $now, $userLoggedIn);
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testToString(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();
    $email = 'string-test@example.com';

    $user = User::create(
      $uuid,
      $email,
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Test __toString method returns email
    self::assertSame($email, (string) $user);
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testCreateThrowsExceptionWhenUserExists(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();

    // Create a mock that will report the user already exists
    $doesUserExist = $this->createMock(DoesUserExist::class);
    $doesUserExist->method('__invoke')
      ->willReturn(true);

    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    // Expect exception when trying to create a user that already exists
    $this->expectException(UserAlreadyExistsException::class);
    User::create(
      $uuid,
      'existing@example.com',
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );
  }

  /**
   * @throws UserAlreadyExistsException
   */
  #[Group('unit')]
  public function testEditThrowsExceptionWhenEmailExists(): void
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $userEdited = $container->getByType(UserEdited::class);
    $passwords = $container->getByType(Passwords::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::uuid4();

    $user = User::create(
      $uuid,
      'original@example.com',
      'password',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    // Create a mock that will report the new email already exists
    $doesUserExistMock = $this->createMock(DoesUserExist::class);
    $doesUserExistMock->method('__invoke')
      ->willReturn(true);

    // Expect exception when trying to edit with existing email
    $this->expectException(UserAlreadyExistsException::class);
    $user->edit('existing-email@example.com', $now->plusDays(30), $doesUserExistMock, $userEdited);
  }
}
