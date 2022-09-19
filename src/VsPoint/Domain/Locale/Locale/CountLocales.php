<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

interface CountLocales
{
  public function __invoke(): int;
}
