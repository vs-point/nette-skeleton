<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Routing;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;
use Nette\Routing\Router;
use VsPoint\Http\Web\Front\HomepagePresenter;

final class RouterFactory
{
  /**
   * @var string[]
   */
  private array $languages;

  private readonly RouteList $router;

  /**
   * @param string[] $languages
   */
  public function __construct(array $languages)
  {
    $this->languages = $languages;
    $this->router = new RouteList();
  }

  public function createRouter(): Router
  {
    $router = $this->router;
    $defaultLang = reset($this->languages);

    $languagePattern = '[<locale=' . $defaultLang . ' ' . implode('|', $this->languages) . '>/]';

    $router->addRoute(
      'admin/' . $languagePattern . '<presenter>[/<action>][/<id>]',
      [
        'module' => 'Admin',
        'presenter' => 'Homepage',
        'action' => [
          Route::Value => 'default',
          Route::Pattern => '[a-z-]+',
        ],
      ]
    );

    $this->addRoute('/', HomepagePresenter::LINK);

    return $this->router;
  }

  /**
   * @param array{'presenter': string, 'action'?: string}|string $metadata
   */
  private function addRoute(string $mask, array|string $metadata, int $flags = 0): void
  {
    if (is_string($metadata)) {
      $metadata = [
        'presenter' => $metadata,
        'action' => 'default',
      ];
    }

    $metadata['presenter'] = trim($metadata['presenter'], ':');
    $metadata['action'] ??= 'default';

    $this->router->addRoute($mask, $metadata, $flags);
  }
}
