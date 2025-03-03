<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use loophp\collection\Collection as LoopCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Ramsey\Uuid\Uuid;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;
use VsPoint\VO\PreFetch\PreFetchUserRoles;

#[CoversClass(PreFetchUserRoles::class)]
final class PreFetchUserRolesTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    /** @var LoopCollection<int, UserRole> $userRoles */
    $userRoles = LoopCollection::empty();
    $preFetchUserRoles = new PreFetchUserRoles($em, $userRoles);

    self::assertCount(0, $preFetchUserRoles->toArray());

    $sequence = $preFetchUserRoles->toCollection();

    self::assertCount(0, $sequence);
  }

  #[Group('unit')]
  public function testByIds(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleId01 = Uuid::fromString(InitFixture::USER_ROLE_POWER_USER);
    $userRolesIds = LoopCollection::fromIterable([$userRoleId01]);
    $preFetchUserRoles = PreFetchUserRoles::byIds($em, $userRolesIds);

    self::assertCount(1, $preFetchUserRoles->toArray());

    $sequence = $preFetchUserRoles->toCollection();

    self::assertCount(1, $sequence);
  }

  #[Group('unit')]
  public function testWithUser(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleId01 = Uuid::fromString(InitFixture::USER_ROLE_POWER_USER);
    $userRolesIds = LoopCollection::fromIterable([$userRoleId01]);
    $preFetchUserRoles = PreFetchUserRoles::byIds($em, $userRolesIds);

    $preFetchUsers = $preFetchUserRoles->withUser();

    self::assertCount(1, $preFetchUsers->toArray());
  }
}
