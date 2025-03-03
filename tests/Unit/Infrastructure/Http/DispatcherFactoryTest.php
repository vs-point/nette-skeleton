<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Nette\Caching\Storages\MemoryStorage;
use VsPoint\Infrastructure\Http\DispatcherFactory;
use VsPoint\Infrastructure\Http\Route;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Infrastructure\Http\DispatcherFactory
 */
final class DispatcherFactoryTest extends TestCase
{
  private DispatcherFactory $dispatcherFactory;

  private MemoryStorage $storage;

  protected function setUp(): void
  {
    $this->storage = new MemoryStorage();
    $this->dispatcherFactory = new DispatcherFactory($this->storage, debugMode: true);
  }

  public function testCreateDispatcherWithoutCache(): void
  {
    $collector = new RouteCollector(
      new \FastRoute\RouteParser\Std(),
      new \FastRoute\DataGenerator\GroupCountBased(),
    );

    $routes = [
      new Route('GET', '/users', 'UserController@index'),
      new Route('POST', '/users', 'UserController@store'),
    ];

    $dispatcher = $this->dispatcherFactory->create($collector, $routes);

    self::assertInstanceOf(GroupCountBased::class, $dispatcher);

    $routeInfo = $dispatcher->dispatch('GET', '/users');
    self::assertSame(Dispatcher::FOUND, $routeInfo[0]);
    self::assertSame('UserController@index', $routeInfo[1]);
  }
}
