<?php

declare(strict_types=1);

namespace Unit\UI\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Set;
use Ds\Vector;
use Ramsey\Uuid\Uuid;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Test\TestCase;
use VsPoint\VO\PreFetch\PreFetchUserRoles;

/**
 * @covers \VsPoint\VO\PreFetch\PreFetchUserRoles
 */
final class PreFetchUserRolesTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $users = new Vector();
    $preFetchUsers = new PreFetchUserRoles($em, $users);

    self::assertCount(0, $preFetchUsers->toArray());

    $sequence = $preFetchUsers->toSequence();

    self::assertCount(0, $sequence);
  }

  /**
   * @group unit
   */
  public function testByIds(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleId01 = Uuid::fromString(InitFixture::USER_ROLE_POWER_USER);
    $userRolesIds = new Set([$userRoleId01]);
    $preFetchUserRoles = PreFetchUserRoles::byIds($em, $userRolesIds);

    self::assertCount(1, $preFetchUserRoles->toArray());

    $sequence = $preFetchUserRoles->toSequence();

    self::assertCount(1, $sequence);
  }

  /**
   * @group unit
   */
  public function testWithUser(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userRoleId01 = Uuid::fromString(InitFixture::USER_ROLE_POWER_USER);
    $userRolesIds = new Set([$userRoleId01]);
    $preFetchUserRoles = PreFetchUserRoles::byIds($em, $userRolesIds);

    $preFetchUsers = $preFetchUserRoles->withUser();

    self::assertCount(1, $preFetchUsers->toArray());
  }
}
