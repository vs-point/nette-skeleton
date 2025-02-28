<?php

declare(strict_types=1);

namespace Unit\Entity;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Acl\Role
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
    $userRoleCreated = $container->getByType(UserRoleCreated::class);

    $now = $clock->createZonedDateTime();
    $uuid = Uuid::fromString(InitFixture::USER_01);
    $pass = 'MFD_mpb3vjw8wcb.tvqa';

    $user = User::create(
      $uuid,
      'test@test.test',
      $pass,
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    self::assertSame('test@test.test', $user->getEmail());
    self::assertTrue($passwords->verify($pass, $user->getPassword()));
    self::assertEquals($now, $user->getCreatedAt());
    self::assertTrue($uuid->equals($user->getId()));
    self::assertEquals($now, $user->getGdpr());
  }
}
