<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use VsPoint\Entity\Locale\Locale;

interface LocaleCreated
{
  public function __invoke(Locale $locale): void;
}
