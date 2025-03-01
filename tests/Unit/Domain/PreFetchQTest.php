<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain;

use VsPoint\Domain\PreFetchQ;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Ds\Vector;
use VsPoint\Domain\PreFetch;
use VsPoint\Test\TestCase;

#[CoversClass(PreFetchQ::class)]
final class PreFetchQTest extends TestCase
{
  #[Group('unit')]
  public function testForUsers(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);
    $preFetchUsers = $preFetch->forUsers(new Vector());

    self::assertCount(0, $preFetchUsers->toArray());
  }

  #[Group('unit')]
  public function testForUserRoles(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);
    $preFetchUserRoles = $preFetch->forUserRoles(new Vector());

    self::assertCount(0, $preFetchUserRoles->toArray());
  }
}
