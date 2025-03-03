<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http\Nette\Security;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Nette\MemberAccessException;
use Nette\Security\Authenticator;
use Nette\Security\Authorizator;
use Nette\Security\IIdentity;
use Nette\Security\User as SecurityUser;
use Nette\Security\UserStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Domain\Acl\User\GetUserByIdQ;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Exception\Runtime\Acl\UserNotLoggedInException;
use VsPoint\Infrastructure\Nette\Security\GetLoggedInUserQ;
use VsPoint\Test\TestCase;

#[CoversClass(GetLoggedInUserQ::class)]
final class GetLoggedInUserQTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @throws UserNotLoggedInException
   * @throws UserNotFound
   * @throws MemberAccessException
   */
  public function testThrowsExceptionIfUserNotLoggedIn(): void
  {
    $getUserByIdMock = Mockery::mock(GetUserById::class);

    $securityMock = $this->getSecurityUser(false, InitFixture::USER_01);

    $getLoggedInUser = new GetLoggedInUserQ($getUserByIdMock, $securityMock);

    $this->expectException(UserNotLoggedInException::class);

    $getLoggedInUser->__invoke();
  }

  /**
   * @throws UserNotLoggedInException
   * @throws UserNotFound
   * @throws MemberAccessException
   */
  public function testThrowsExceptionIfUserNotFound(): void
  {
    $securityMock = $this->getSecurityUser(true, '550e8400-e29b-41d4-a716-446655440000');

    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('find')->andReturnNull();

    $getUserByIdQ = new GetUserByIdQ($emMock);

    $getLoggedInUser = new GetLoggedInUserQ($getUserByIdQ, $securityMock);

    $this->expectException(UserNotFound::class);

    $getLoggedInUser->__invoke();
  }

  /**
   * @throws UserNotLoggedInException
   * @throws UserNotFound
   * @throws MemberAccessException
   */
  public function testReturnsUserWhenLoggedIn(): void
  {
    $securityMock = $this->getSecurityUser(true, InitFixture::USER_01);

    $container = $this->createContainer();

    $getUserByIdQ = $container->getByType(GetUserByIdQ::class);

    $getLoggedInUser = new GetLoggedInUserQ($getUserByIdQ, $securityMock);

    $getLoggedInUser = $getLoggedInUser->__invoke();

    self::assertSame(InitFixture::USER_01, $getLoggedInUser->getId()->toString());
  }

  /**
   * @param array<string> $roles
   *
   * @throws MemberAccessException
   */
  private function getSecurityUser(
    bool $loggedIn = false,
    ?string $userId = null,
    array $roles = [],
  ): SecurityUser {
    $storageMock = Mockery::mock(UserStorage::class);
    $authenticatorMock = Mockery::mock(Authenticator::class);
    $authorizatorMock = Mockery::mock(Authorizator::class);

    $userMock = Mockery::mock(
      SecurityUser::class,
      [$storageMock, $authenticatorMock, $authorizatorMock]
    )->makePartial();

    $identityMock = Mockery::mock(IIdentity::class);
    $identityMock->allows('getId')->andReturns($userId);
    $identityMock->allows('getRoles')->andReturns($roles);

    $storageMock->allows('getState')->andReturns([$loggedIn, $identityMock, null]);

    return $userMock;
  }
}
