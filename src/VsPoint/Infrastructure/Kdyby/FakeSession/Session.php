<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Kdyby\FakeSession;

use ArrayIterator;
use Iterator;
use LogicException;
use Nette\Http\Session as NetteSession;
use Nette\Http\SessionSection as NetteSessionSection;
use Override;
use SessionHandlerInterface;

class Session extends NetteSession
{
  /**
   * @var NetteSessionSection[]
   */
  private array $sections = [];

  private bool $started = false;

  private bool $exists = false;

  private string $id = '';

  private readonly NetteSession $originalSession;

  private bool $fakeMode = false;

  public function __construct(NetteSession $originalSession)
  {
    $this->originalSession = $originalSession;
  }

  public function disableNative(): void
  {
    if ($this->originalSession->isStarted()) {
      throw new LogicException(
        'Session is already started, please close it first and then you can disabled it.'
      );
    }

    $this->fakeMode = true;
  }

  #[Override]
  public function start(): void
  {
    if (!$this->fakeMode) {
      $this->originalSession->start();
    }
  }

  #[Override]
  public function isStarted(): bool
  {
    if (!$this->fakeMode) {
      return $this->originalSession->isStarted();
    }

    return $this->started;
  }

  #[Override]
  public function close(): void
  {
    if (!$this->fakeMode) {
      $this->originalSession->close();
    }
  }

  #[Override]
  public function destroy(): void
  {
    if (!$this->fakeMode) {
      $this->originalSession->destroy();
    }
  }

  #[Override]
  public function exists(): bool
  {
    if (!$this->fakeMode) {
      return $this->originalSession->exists();
    }

    return $this->exists;
  }

  #[Override]
  public function regenerateId(): void
  {
    if (!$this->fakeMode) {
      $this->originalSession->regenerateId();
    }
  }

  #[Override]
  public function getId(): string
  {
    if (!$this->fakeMode) {
      return $this->originalSession->getId();
    }

    return $this->id;
  }

  #[Override]
  public function getSection(string $section, string $class = NetteSessionSection::class): NetteSessionSection
  {
    if (!$this->fakeMode) {
      return $this->originalSession->getSection($section, $class);
    }

    return $this->sections[$section] ?? $this->sections[$section] = parent::getSection(
      $section,
      $class !== NetteSessionSection::class ? $class : SessionSection::class
    );
  }

  #[Override]
  public function hasSection(string $section): bool
  {
    if (!$this->fakeMode) {
      return $this->originalSession->hasSection($section);
    }

    return isset($this->sections[$section]);
  }

  public function getIterator(): Iterator
  {
    return new ArrayIterator(array_keys($this->sections));
  }

  #[Override]
  public function setName(string $name): static
  {
    $this->originalSession->setName($name);

    return $this;
  }

  #[Override]
  public function getName(): string
  {
    return $this->originalSession->getName();
  }

  /**
   * @param mixed[] $options
   */
  #[Override]
  public function setOptions(array $options): static
  {
    $this->originalSession->setOptions($options);

    return $this;
  }

  /**
   * @return mixed[]
   */
  #[Override]
  public function getOptions(): array
  {
    return $this->originalSession->getOptions();
  }

  #[Override]
  public function setExpiration(?string $time): static
  {
    $this->originalSession->setExpiration($time);

    return $this;
  }

  #[Override]
  public function setCookieParameters(
    string $path,
    ?string $domain = null,
    ?bool $secure = null,
    ?string $sameSite = null,
  ): static {
    $this->originalSession->setCookieParameters($path, $domain, $secure, $sameSite);

    return $this;
  }

  #[Override]
  public function setSavePath(string $path): static
  {
    $this->originalSession->setSavePath($path);

    return $this;
  }

  #[Override]
  public function setHandler(SessionHandlerInterface $handler): static
  {
    $this->originalSession->setHandler($handler);

    return $this;
  }
}
