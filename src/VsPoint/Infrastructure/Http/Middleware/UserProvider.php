<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Http\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Exception\Runtime\Acl\UserNotFound;

use function array_key_exists;
use function is_array;

final readonly class UserProvider implements MiddlewareInterface
{
  /**
   * @var string
   */
  public const string ATTR_USER = 'user';

  private GetUserById $getUserById;

  public function __construct(GetUserById $getUserById)
  {
    $this->getUserById = $getUserById;
  }

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $jwt = $request->getAttribute('jwt');

    if ($jwt !== null) {
      assert(is_array($jwt));
      assert(array_key_exists('id', $jwt));

      $id = $jwt['id'];

      assert(is_string($id));

      try {
        $user = $this->getUserById->__invoke(Uuid::fromString($id));
        $request = $request->withAttribute(self::ATTR_USER, $user);
      } catch (UserNotFound $e) {
        return new JsonResponse([
          'error' => [
            'message' => $e->getMessage(),
          ],
        ], 401);
      }
    }

    return $handler->handle($request);
  }
}
