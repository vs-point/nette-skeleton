<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

use JsonSerializable;
use Stringable;

final readonly class GtmVO
{
  private string $page;

  /**
   * @var array<JsonSerializable|string|Stringable>
   */
  private array $events;

  /**
   * @param array<JsonSerializable|string|Stringable> $events
   */
  public function __construct(string $page, array $events = [])
  {
    $this->page = $page;
    $this->events = $events;
  }

  public function getPage(): string
  {
    return $this->page;
  }

  public function hasEvents(): bool
  {
    return count($this->getEvents()) > 0;
  }

  /**
   * @return array<JsonSerializable|string|Stringable>
   */
  public function getEvents(): array
  {
    return $this->events;
  }
}
