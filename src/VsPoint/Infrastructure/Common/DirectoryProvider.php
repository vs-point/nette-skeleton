<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Common;

class DirectoryProvider
{
  public function __construct(
    public readonly string $appDir,
    public readonly string $tempDir,
    public readonly string $vendorDir,
  ) {
  }
}
