<?php

declare(strict_types=1);

namespace Unit\Entity;

use Contributte\Console\Application;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\User;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Acl\Role
 */
final class UserTest extends TestCase
{
  /**
   * @group unit
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
      Uuid::uuid4(),
      'test@test.test',
      $pass,
      null,
      $now,
      null,
      $doesUserExist,
      $passwords,
      $userCreated
    );

    self::assertSame('test@test.test', $user->getEmail());
    self::assertTrue($passwords->verify($pass, $user->getPassword()));
    self::assertTrue($now->isEqualTo($user->getCreatedAt()));
    self::assertTrue($uuid->equals($user->getId()));
    self::assertNull($user->getGdpr());
    self::assertNull($user->getGdpr());
  }
}
