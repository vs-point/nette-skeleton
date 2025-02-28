<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserByEmail;
use VsPoint\Domain\Acl\User\GetUserByEmailQ;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\GetUserByEmailQ
 */
final class GetUserByEmailQTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $getUserByEmailQ = new GetUserByEmailQ($em);

    // @phpstan-ignore-next-line
    self::assertInstanceOf(GetUserByEmail::class, $getUserByEmailQ);
  }

  /**
   * @group unit
   * @throws UserNotFoundByEmail
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();

    $getUserByEmailQ = $container->getByType(GetUserByEmailQ::class);

    $email = InitFixture::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ;
    $user = $getUserByEmailQ->__invoke($email);

    self::assertEquals($email, $user->getEmail());
  }
}
