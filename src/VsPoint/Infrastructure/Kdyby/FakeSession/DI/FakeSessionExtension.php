<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Kdyby\FakeSession\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Http\Session as NetteSession;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Override;
use VsPoint\Infrastructure\Kdyby\FakeSession\Session;

use function assert;

use const PHP_SAPI;

class FakeSessionExtension extends CompilerExtension
{
  public function __construct()
  {
  }

  #[Override]
  public function getConfigSchema(): Schema
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

    assert($original instanceof ServiceDefinition);

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
