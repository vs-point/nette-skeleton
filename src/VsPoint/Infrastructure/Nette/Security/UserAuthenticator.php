<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\GetUserByEmail;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Domain\Acl\User\UserLoggedIn;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Exception\Runtime\Authentication\InvalidPasswordException;
use VsPoint\Exception\Runtime\Authentication\UserInactiveException;

use function is_string;

final readonly class UserAuthenticator implements Authenticator, IdentityHandler
{
  public function __construct(
    private Passwords $passwords,
    private GetUserById $getUserById,
    private GetUserByEmail $getUserByEmail,
    private UserLoggedIn $userLoggedIn,
    private Clock $clock,
  ) {
  }

  /**
   * @throw s AuthenticationException <-- PHPStan bug?
   */
  public function authenticate(string $user, string $password): IIdentity
  {
    try {
      $userE = $this->getUserByEmail->__invoke($user);
    } catch (UserNotFoundByEmail $e) {
      throw new AuthenticationException('The username is incorrect.', self::IdentityNotFound, $e);
    }

    $timestamp = $this->clock->createZonedDateTime();

    try {
      $userE->logIn($this->passwords, $password, $timestamp, $this->userLoggedIn);
    } catch (InvalidPasswordException $e) {
      throw new AuthenticationException('The password is incorrect.', self::InvalidCredential, $e);
    } catch (UserInactiveException $e) {
      throw new AuthenticationException('The user is inactive.', self::NotApproved, $e);
    }

    return Identity::from($userE);
  }

  public function sleepIdentity(IIdentity $identity): IIdentity
  {
    if ($identity instanceof Identity) {
      $id = $identity->getId()->toString();

      return new SimpleIdentity($id);
    }

    return $identity;
  }

  public function wakeupIdentity(IIdentity $identity): ?IIdentity
  {
    if ($identity instanceof SimpleIdentity) {
      $uuid = $identity->getId();
      assert(is_string($uuid));
      $id = Uuid::fromString($uuid);

      try {
        $user = $this->getUserById->__invoke($id);

        return Identity::from($user);
      } catch (UserNotFound) {
        return null;
      }
    }

    return $identity;
  }
}
