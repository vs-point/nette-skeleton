<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\VO\PreFetch;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Doctrine\ORM\EntityManagerInterface;
use Ds\Set;
use Ds\Vector;
use Ramsey\Uuid\Uuid;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Test\TestCase;
use VsPoint\VO\PreFetch\PreFetchUsers;

#[CoversClass(PreFetchUsers::class)]
final class PreFetchUsersTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $users = new Vector();
    $preFetchUsers = new PreFetchUsers($em, $users);

    self::assertCount(0, $preFetchUsers->toArray());

    $sequence = $preFetchUsers->toSequence();

    self::assertCount(0, $sequence);
  }

  #[Group('unit')]
  public function testByIds(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userId01 = Uuid::fromString(InitFixture::USER_01);
    $userIds = new Set([$userId01]);
    $preFetchUsers = PreFetchUsers::byIds($em, $userIds);

    self::assertCount(1, $preFetchUsers->toArray());

    $sequence = $preFetchUsers->toSequence();

    self::assertCount(1, $sequence);
  }

  #[Group('unit')]
  public function testWithUserRoles(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userId01 = Uuid::fromString(InitFixture::USER_01);
    $userIds = new Set([$userId01]);
    $preFetchUsers = PreFetchUsers::byIds($em, $userIds);

    $preFetchUserRoles = $preFetchUsers->withUserRoles();

    self::assertCount(1, $preFetchUserRoles->toArray());
  }
}
