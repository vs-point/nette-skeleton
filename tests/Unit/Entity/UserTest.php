<?php

declare(strict_types=1);

namespace Unit\Entity;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Acl\User
 */
final class UserTest extends TestCase
{
  /**
   * @group unit
   *
   * @throws UserAlreadyExistsException
   */
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
}
