<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Kdyby\FakeSession;

use ArrayIterator;
use Iterator;
use Nette\Http\Session as NetteSession;

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

  /**
   * @param mixed $value
   */
  public function __set(string $name, $value): void
  {
    $this->data[$name] = $value;
  }

  public function __isset(string $name): bool
  {
    return isset($this->data[$name]);
  }

  public function __unset(string $name): void
  {
    unset($this->data[$name]);
  }

  public function getIterator(): Iterator
  {
    return new ArrayIterator($this->data);
  }

  /**
   * @return mixed
   */
  public function &__get(string $name)
  {
    if ($this->warnOnUndefined && !array_key_exists($name, $this->data)) {
      trigger_error(sprintf("The variable '%s' does not exist in session section", $name), E_USER_NOTICE);
    }

    return $this->data[$name];
  }

  /**
   * @param null|string $time
   * @param null|string|string[] $variables list of variables / single variable to expire
   *
   * @return static
   */
  public function setExpiration($time, $variables = null): self
  {
    return $this;
  }

  /**
   * @param null|string|string[] $variables list of variables / single variable to expire
   */
  public function removeExpiration($variables = null): void
  {
  }

  public function remove($name = null): void
  {
    $this->data = [];
  }
}
