<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Domain\Acl\User\FindUsers;
use VsPoint\Domain\Acl\User\FindUsersQ;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\FindUsersQ
 */
final class FindUsersQTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $findUsersQ = new FindUsersQ($em);

    // @phpstan-ignore-next-line
    self::assertInstanceOf(FindUsers::class, $findUsersQ);
  }

  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();

    $findUsersQ = $container->getByType(FindUsersQ::class);

    $users = $findUsersQ->__invoke();

    self::assertCount(1, $users);
  }
}
