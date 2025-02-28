<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Acl\User;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as DoctrineNonUniqueResultException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserByEmail;
use VsPoint\Domain\Acl\User\GetUserByEmailQ;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\GetUserByEmailQ
 */
final class GetUserByEmailQTest extends TestCase
{
  use MockeryPHPUnitIntegration;

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

    self::assertSame($email, $user->getEmail());
  }

  /**
   * @group unit
   *
   * @throws UserNotFoundByEmail
   */
  public function testInvokeNotFound(): void
  {
    $this->expectException(UserNotFoundByEmail::class);

    $container = $this->createContainer();

    $getUserByEmailQ = $container->getByType(GetUserByEmailQ::class);

    $getUserByEmailQ->__invoke('not.exists@email.com');
  }

  /**
   * @group unit
   *
   * @throws UserNotFoundByEmail
   */
  public function testNonUniqueResult(): void
  {
    $this->expectException(NonUniqueResultException::class);

    $emMock = Mockery::mock(EntityManagerInterface::class);

    $exception = new DoctrineNonUniqueResultException('Non unique mock exception.');

    $queryMock = Mockery::mock(AbstractQuery::class);
    $queryMock
      ->allows('setParameter')
      ->andReturnSelf()
    ;

    $queryMock
      ->allows('getSingleResult')
      ->andThrows($exception)
    ;

    $emMock
      ->allows('createQuery')
      ->andReturns($queryMock)
    ;

    $doesUserExist = new GetUserByEmailQ($emMock);
    $doesUserExist->__invoke('test@email.com');
  }
}
