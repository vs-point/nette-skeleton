<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http\Middleware;

use function Safe\json_decode;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Nette\Security\Passwords;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Infrastructure\Http\Middleware\UserProvider;
use VsPoint\Test\TestCase;

final class UserProviderTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @throws UserAlreadyExistsException
   * @covers \VsPoint\Infrastructure\Http\Middleware\UserProvider
   */
  public function testProcessWithValidUser(): void
  {
    $getUserByIdMock = Mockery::mock(GetUserById::class);
    $middleware = new UserProvider($getUserByIdMock);

    $uuid = Uuid::fromString(InitFixture::USER_01);
    $userMock = $this->getUser();

    $getUserByIdMock
      ->allows('__invoke')
      ->andReturn($userMock);

    $request = (new ServerRequest())->withAttribute('jwt', [
      'id' => $uuid->toString(),
    ]);

    $handlerMock = Mockery::mock(RequestHandlerInterface::class);
    $handlerMock->shouldReceive('handle')
      ->withArgs(fn($req): bool => $req->getAttribute(UserProvider::ATTR_USER) === $userMock)
      ->andReturn(new Response());

    $response = $middleware->process($request, $handlerMock);

    self::assertInstanceOf(Response::class, $response);
  }

  public function testProcessWithUserNotFound(): void
  {
    $getUserByIdMock = Mockery::mock(GetUserById::class);
    $middleware = new UserProvider($getUserByIdMock);

    $uuid = Uuid::uuid4();

    $getUserByIdMock
      ->allows('__invoke')
      ->andThrow(new UserNotFound($uuid));

    $request = (new ServerRequest())->withAttribute('jwt', [
      'id' => $uuid->toString(),
    ]);

    $handlerMock = Mockery::mock(RequestHandlerInterface::class);

    $response = $middleware->process($request, $handlerMock);

    self::assertInstanceOf(JsonResponse::class, $response);
    self::assertSame(401, $response->getStatusCode());

    $data = json_decode((string) $response->getBody(), true);
    self::assertStringStartsWith('User was not found for id: ', $data['error']['message']);
  }

  public function testProcessWithoutJwt(): void
  {
    $getUserByIdMock = Mockery::mock(GetUserById::class);
    $middleware = new UserProvider($getUserByIdMock);

    $request = new ServerRequest(); // Žádný JWT atribut

    $handlerMock = Mockery::mock(RequestHandlerInterface::class);
    $handlerMock->shouldReceive('handle')
      ->with($request)
      ->andReturn(new Response());

    $response = $middleware->process($request, $handlerMock);

    self::assertInstanceOf(Response::class, $response);
  }

  /**
   * @throws UserAlreadyExistsException
   */
  private function getUser(): User
  {
    $container = $this->createContainer();

    $clock = $container->getByType(Clock::class);
    $doesUserExist = $container->getByType(DoesUserExist::class);
    $userCreated = $container->getByType(UserCreated::class);
    $passwords = $container->getByType(Passwords::class);

    $userId = Uuid::uuid4();
    $now = $clock->createZonedDateTime();

    return User::create(
      $userId,
      'test@example.com',
      'password123',
      null,
      $now,
      $now,
      $doesUserExist,
      $passwords,
      $userCreated
    );
  }
}
