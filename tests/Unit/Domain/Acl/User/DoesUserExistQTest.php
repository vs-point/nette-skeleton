<?php

declare(strict_types=1);

namespace Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\DoesUserExistQ;
use VsPoint\Entity\Acl\User;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\DoesUserExistQ
 */
final class DoesUserExistQTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $doesUserExist = new DoesUserExistQ($em);

    // @phpstan-ignore-next-line
    self::assertInstanceOf(DoesUserExist::class, $doesUserExist);
  }

  /**
   * @group unit
   */
  public function testExists(): void
  {
    $container = $this->createContainer();

    $doesUserExist = $container->getByType(DoesUserExistQ::class);

    self::assertTrue($doesUserExist->__invoke(InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ));
  }

  /**
   * @group unit
   */
  public function testExistsWithUser(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $user = $em->find(User::class, InitFixture::USER_01);

    $doesUserExist = $container->getByType(DoesUserExistQ::class);

    self::assertFalse($doesUserExist->__invoke(InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ, $user));
  }

  /**
   * @group unit
   */
  public function testDoesNotExist(): void
  {
    $container = $this->createContainer();

    $doesUserExist = $container->getByType(DoesUserExistQ::class);

    self::assertFalse($doesUserExist->__invoke('random@email.com'));
  }
}
