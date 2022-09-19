<?php

declare(strict_types=1);

namespace VsPoint\DTO\Common;

final class SearchFormDTO
{
  public function __construct(
    public readonly string $query,
  ) {
  }
}
