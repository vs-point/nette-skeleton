<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Kdyby\FakeSession\DI;

use Nette;
use Nette\DI\CompilerExtension;
use Nette\Http\Session as NetteSession;
use Nette\Schema\Expect;
use VsPoint\Infrastructure\Kdyby\FakeSession\Session;

use function assert;

use const PHP_SAPI;

class FakeSessionExtension extends CompilerExtension
{
  public function __construct()
  {
  }

  public function getConfigSchema(): Nette\Schema\Schema
  {
    return Expect::structure(
      [
        'enabled' => Expect::bool()->default(PHP_SAPI === 'cli'),
      ]
    )->castTo(FakeSessionConfig::class);
  }

  public function beforeCompile(): void
  {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig();
    assert($config instanceof FakeSessionConfig);

    $originalServiceName = $builder->getByType(NetteSession::class) ?? 'session';
    $original = $builder->getDefinition($originalServiceName);
    $builder->removeDefinition($originalServiceName);

    assert($original instanceof Nette\DI\Definitions\ServiceDefinition);

    $backupOriginalName = $this->prefix('original');
    $builder
      ->addDefinition($backupOriginalName)
      ->setFactory($original->getFactory())
      ->setType($original->getType())
      ->setTags($original->getTags())
      ->setAutowired(false)
    ;

    $session = $builder
      ->addDefinition($originalServiceName)
      ->setType(NetteSession::class)
      ->setFactory(Session::class, [$this->prefix('@original')])
    ;

    if ($config->enabled) {
      $session->addSetup('disableNative');
    }
  }
}
