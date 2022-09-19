<?php

declare(strict_types=1);

namespace VsPoint\Http\Api;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tracy\Debugger;

final class Ping implements MiddlewareInterface
{
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    $query = $request->getQueryParams();
    $format = $query['format'] ?? 'html';

    if ($format === 'json') {
      return new JsonResponse([
        'word' => 'ping',
      ]);
    }

    Debugger::barDump($request->getQueryParams());
    Debugger::barDump($request->getAttributes());
    Debugger::barDump($request->getBody());
    Debugger::barDump($request->getCookieParams());
    Debugger::barDump($request->getParsedBody());
    Debugger::barDump($request->getServerParams());

    return new HtmlResponse('<html lang="en"><body><h1>PING</h1></body></html>');
  }
}
