<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Kdyby\FakeSession;

use ArrayIterator;
use Iterator;
use Nette\Http\Session as NetteSession;
use Override;

class SessionSection extends \Nette\Http\SessionSection
{
  /**
   * @var mixed[]
   */
  private $data = [];

  public function __construct(NetteSession $session, string $name)
  {
    parent::__construct($session, $name);
  }

  #[Override]
  public function __set(string $name, mixed $value): void
  {
    $this->data[$name] = $value;
  }

  #[Override]
  public function __isset(string $name): bool
  {
    return isset($this->data[$name]);
  }

  #[Override]
  public function __unset(string $name): void
  {
    unset($this->data[$name]);
  }

  #[Override]
  public function getIterator(): Iterator
  {
    return new ArrayIterator($this->data);
  }

  #[Override]
  public function &__get(string $name): mixed
  {
    if ($this->warnOnUndefined && !array_key_exists($name, $this->data)) {
      trigger_error(sprintf("The variable '%s' does not exist in session section", $name), E_USER_NOTICE);
    }

    return $this->data[$name];
  }

  /**
   * @param string|string[]|null $variables list of variables / single variable to expire
   */
  #[Override]
  public function setExpiration(?string $expire, string|array|null $variables = null): static
  {
    return $this;
  }

  /**
   * @param string|string[]|null $variables list of variables / single variable to expire
   */
  #[Override]
  public function removeExpiration($variables = null): void
  {
  }

  #[Override]
  public function remove($name = null): void
  {
    $this->data = [];
  }
}
