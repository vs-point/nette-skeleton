<?php

declare(strict_types=1);

namespace VsPoint\Http\Api;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Pong implements MiddlewareInterface
{
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    $body = $request->getParsedBody();

    return new JsonResponse($body);
  }
}
