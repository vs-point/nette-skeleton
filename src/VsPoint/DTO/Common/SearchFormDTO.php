<?php

declare(strict_types=1);

namespace VsPoint\DTO\Common;

final readonly class SearchFormDTO
{
  public function __construct(
    public string $query,
  ) {
  }
}
