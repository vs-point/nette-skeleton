<?php

declare(strict_types=1);

namespace Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\UserORM;
use VsPoint\Entity\Acl\User;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\UserORM
 */
final class UserORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $userORM = new UserORM($em);
  }

  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $user = $em->find(User::class, InitFixture::USER_01);
    self::assertInstanceOf(User::class, $user);

    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('persist')->once();

    $userORM = new UserORM($emMock);

    $userORM->__invoke($user);
  }
}
