<?php

declare(strict_types=1);

namespace Unit\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Set;
use Ds\Vector;
use VsPoint\Test\TestCase;
use VsPoint\VO\PreFetch\PreFetchUsers;

/**
 * @covers \VsPoint\VO\PreFetch\PreFetchUsers
 */
final class PreFetchUsersTest extends TestCase
{
  /**
   * @group unit
   */
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

  /**
   * @group unit
   */
  public function testByIds(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userIds = new Set();
    $preFetchUsers = PreFetchUsers::byIds($em, $userIds);

    self::assertCount(0, $preFetchUsers->toArray());

    $sequence = $preFetchUsers->toSequence();

    self::assertCount(0, $sequence);
  }

  /**
   * @group unit
   */
  public function testWithUserRoles(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userIds = new Set();
    $preFetchUsers = PreFetchUsers::byIds($em, $userIds);

    $preFetchUserRoles = $preFetchUsers->withUserRoles();

    self::assertCount(0, $preFetchUserRoles->toArray());
  }
}
