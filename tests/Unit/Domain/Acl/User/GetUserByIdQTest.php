<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Domain\Acl\User\GetUserByIdQ;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\GetUserByIdQ
 */
final class GetUserByIdQTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $getUserByIdQ = new GetUserByIdQ($em);

    // @phpstan-ignore-next-line
    self::assertInstanceOf(GetUserById::class, $getUserByIdQ);
  }

  /**
   * @group unit
   *
   * @throws UserNotFound
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();

    $getUserByIdQ = $container->getByType(GetUserByIdQ::class);

    $id = Uuid::fromString(InitFixture::USER_01);
    $user = $getUserByIdQ->__invoke($id);

    self::assertEquals($id, $user->getId());
  }

  /**
   * @group unit
   *
   * @throws UserNotFound
   */
  public function testInvokeNotFound(): void
  {
    $this->expectException(UserNotFound::class);

    $container = $this->createContainer();

    $getUserByIdQ = $container->getByType(GetUserByIdQ::class);

    $id = Uuid::fromString('7cbe6689-c240-4a7e-864c-877626636ffc');
    $getUserByIdQ->__invoke($id);
  }
}
