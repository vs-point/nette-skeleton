<?php

declare(strict_types=1);

namespace Unit\Domain;

use Ds\Vector;
use VsPoint\Domain\PreFetch;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\PreFetchQ
 */
final class PreFetchQTest extends TestCase
{
  /**
   * @group unit
   */
  public function testForUsers(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);
    $preFetchUsers = $preFetch->forUsers(new Vector());

    self::assertCount(0, $preFetchUsers->toArray());
  }

  /**
   * @group unit
   */
  public function testForUserRoles(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);
    $preFetchUserRoles = $preFetch->forUserRoles(new Vector());

    self::assertCount(0, $preFetchUserRoles->toArray());
  }
}
