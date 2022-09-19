<?php

declare(strict_types=1);

namespace VsPoint\VO\Html;

final class GtmVO
{
  private string $page;

  private array $events;

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

  public function getEvents(): array
  {
    return $this->events;
  }
}
