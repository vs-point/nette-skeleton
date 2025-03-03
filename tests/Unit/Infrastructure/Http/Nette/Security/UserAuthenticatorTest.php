<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http\Nette\Security;

use Doctrine\ORM\NoResultException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use PHPUnit\Framework\Attributes\CoversClass;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserByEmail;
use VsPoint\Domain\Acl\User\GetUserByEmailQ;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Domain\Acl\User\GetUserByIdQ;
use VsPoint\Domain\Acl\User\UserLoggedIn;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Infrastructure\Nette\Security\Identity;
use VsPoint\Infrastructure\Nette\Security\UserAuthenticator;
use VsPoint\Test\TestCase;

#[CoversClass(UserAuthenticator::class)]
final class UserAuthenticatorTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @throws AuthenticationException
   */
  public function testAuthenticateThrowsExceptionIfUserNotFound(): void
  {
    $passwordsMock = Mockery::mock(Passwords::class);
    $getUserByEmailMock = Mockery::mock(GetUserByEmail::class);
    $getUserByIdMock = Mockery::mock(GetUserById::class);
    $userLoggedInMock = Mockery::mock(UserLoggedIn::class);
    $clockMock = Mockery::mock(Clock::class);

    $userNotFoundByEmail = new UserNotFoundByEmail('user@example.com', new NoResultException());

    $getUserByEmailMock->shouldReceive('__invoke')->andThrow($userNotFoundByEmail);

    $authenticator = new UserAuthenticator(
      $passwordsMock,
      $getUserByIdMock,
      $getUserByEmailMock,
      $userLoggedInMock,
      $clockMock
    );

    $this->expectException(AuthenticationException::class);

    $authenticator->authenticate('user@example.com', 'password');
  }

  /**
   * @throws AuthenticationException
   */
  public function testAuthenticateThrowsExceptionIfPasswordInvalid(): void
  {
    $container = $this->createContainer();

    $passwords = $container->getByType(Passwords::class);
    $getUserById = $container->getByType(GetUserById::class);
    $getUserByEmail = $container->getByType(GetUserByEmail::class);
    $userLoggedIn = $container->getByType(UserLoggedIn::class);
    $clock = $container->getByType(Clock::class);

    $this->expectException(AuthenticationException::class);

    $authenticator = new UserAuthenticator($passwords, $getUserById, $getUserByEmail, $userLoggedIn, $clock);
    $authenticator->authenticate(InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ, 'wrongpassword');
  }

  /**
   * @throws AuthenticationException
   */
  public function testAuthenticateReturnsIdentityOnSuccess(): void
  {
    $container = $this->createContainer();

    $passwords = $container->getByType(Passwords::class);
    $getUserByEmail = $container->getByType(GetUserByEmail::class);
    $getUserById = $container->getByType(GetUserById::class);
    $userLoggedIn = $container->getByType(UserLoggedIn::class);
    $clock = $container->getByType(Clock::class);

    $this->expectException(AuthenticationException::class);

    $authenticator = new UserAuthenticator($passwords, $getUserById, $getUserByEmail, $userLoggedIn, $clock);
    $authenticator->authenticate(InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ, 'MFD_mpb3vjw8wcb.tvqa');
  }

  public function testSleepIdentityAsSimpleIdentity(): void
  {
    $authenticator = new UserAuthenticator(
      Mockery::mock(Passwords::class),
      Mockery::mock(GetUserById::class),
      Mockery::mock(GetUserByEmail::class),
      Mockery::mock(UserLoggedIn::class),
      Mockery::mock(Clock::class)
    );

    $identity = new Identity(Uuid::uuid4(), InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ, []);

    $simpleIdentity = $authenticator->sleepIdentity($identity);

    self::assertInstanceOf(SimpleIdentity::class, $simpleIdentity);
  }

  public function testSleepIdentityAsIdentity(): void
  {
    $authenticator = new UserAuthenticator(
      Mockery::mock(Passwords::class),
      Mockery::mock(GetUserById::class),
      Mockery::mock(GetUserByEmail::class),
      Mockery::mock(UserLoggedIn::class),
      Mockery::mock(Clock::class)
    );

    // @phpstan-ignore-next-line
    $identity = new \Nette\Security\Identity(12345, []);

    $simpleIdentity = $authenticator->sleepIdentity($identity);

    // @phpstan-ignore-next-line
    self::assertInstanceOf(\Nette\Security\Identity::class, $simpleIdentity);
  }

  public function testWakeupIdentityUserNotFound(): void
  {
    $getUserByIdMock = Mockery::mock(GetUserById::class);
    $getUserByIdMock->shouldReceive('__invoke')->andThrow(new UserNotFound(Uuid::uuid4()));

    $authenticator = new UserAuthenticator(
      Mockery::mock(Passwords::class),
      $getUserByIdMock,
      Mockery::mock(GetUserByEmail::class),
      Mockery::mock(UserLoggedIn::class),
      Mockery::mock(Clock::class)
    );

    $simpleIdentity = new SimpleIdentity(Uuid::uuid4()->toString());

    self::assertNull($authenticator->wakeupIdentity($simpleIdentity));
  }

  public function testWakeupIdentitySuccess(): void
  {
    $container = $this->createContainer();

    $authenticator = new UserAuthenticator(
      $container->getByType(Passwords::class),
      $container->getByType(GetUserByIdQ::class),
      $container->getByType(GetUserByEmailQ::class),
      $container->getByType(UserLoggedIn::class),
      $container->getByType(Clock::class),
    );

    $simpleIdentity = new SimpleIdentity(InitFixture::USER_01);

    self::assertInstanceOf(Identity::class, $authenticator->wakeupIdentity($simpleIdentity));
  }
}
