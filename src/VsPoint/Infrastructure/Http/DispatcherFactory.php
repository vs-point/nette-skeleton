<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Http;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class DispatcherFactory
{
  private Cache $cache;

  private bool $debugMode;

  public function __construct(Storage $storage, bool $debugMode = false)
  {
    $this->cache = new Cache($storage, 'http.api.dispatcher');
    $this->debugMode = $debugMode;
  }

  /**
   * @param Route[] $routes
   */
  public function create(RouteCollector $collector, array $routes): Dispatcher
  {
    $data = null;

    if (!$this->debugMode) {
      $data = $this->cache->load('routes');
    }

    if ($data === null) {
      foreach ($routes as $route) {
        $collector->addRoute($route->getMethod(), $route->getPath(), $route->getHandler());
      }

      $data = $collector->getData();

      if (!$this->debugMode) {
        $this->cache->save('routes', $data, [
          Cache::FILES => [__DIR__ . '/../../../config/api/config/routes.php'],
        ]);
      }
    }

    return new GroupCountBased($data);
  }
}
