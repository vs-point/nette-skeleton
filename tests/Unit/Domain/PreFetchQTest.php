<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain;

use loophp\collection\Collection as LoopCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Domain\PreFetch;
use VsPoint\Domain\PreFetchQ;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Test\TestCase;

#[CoversClass(PreFetchQ::class)]
final class PreFetchQTest extends TestCase
{
  #[Group('unit')]
  public function testForUsers(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);

    /** @var LoopCollection<int, User> $users */
    $users = LoopCollection::empty();

    $preFetchUsers = $preFetch->forUsers($users);

    self::assertCount(0, $preFetchUsers->toArray());
  }

  #[Group('unit')]
  public function testForUserRoles(): void
  {
    $container = $this->createContainer();

    $preFetch = $container->getByType(PreFetch::class);

    /** @var LoopCollection<int, UserRole> $userRoles */
    $userRoles = LoopCollection::empty();

    $preFetchUserRoles = $preFetch->forUserRoles($userRoles);

    self::assertCount(0, $preFetchUserRoles->toArray());
  }
}
