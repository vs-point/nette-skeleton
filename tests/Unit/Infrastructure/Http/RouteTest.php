<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http;

use PHPUnit\Framework\TestCase;
use VsPoint\Infrastructure\Http\Route;

/**
 * @covers \VsPoint\Infrastructure\Http\Route
 */
final class RouteTest extends TestCase
{
  /**
   * @group unit
   */
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

  /**
   * @group unit
   */
  public function testDifferentRouteValues(): void
  {
    $route = new Route('POST', '/login', 'AuthController@login');

    self::assertSame('POST', $route->getMethod());
    self::assertSame('/login', $route->getPath());
    self::assertSame('AuthController@login', $route->getHandler());
  }
}
