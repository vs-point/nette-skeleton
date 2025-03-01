<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Infrastructure\Http\Route;
use VsPoint\Test\TestCase;

#[CoversClass(Route::class)]
final class RouteTest extends TestCase
{
  #[Group('unit')]
  public function testRouteInitialization(): void
  {
    $method = 'GET';
    $path = '/users';
    $handler = 'UserController@index';

    $route = new Route($method, $path, $handler);

    // Ověření, že jsou hodnoty správně uloženy
    self::assertSame($method, $route->getMethod());
    self::assertSame($path, $route->getPath());
    self::assertSame($handler, $route->getHandler());
  }

  #[Group('unit')]
  public function testDifferentRouteValues(): void
  {
    $route = new Route('POST', '/login', 'AuthController@login');

    self::assertSame('POST', $route->getMethod());
    self::assertSame('/login', $route->getPath());
    self::assertSame('AuthController@login', $route->getHandler());
  }
}
