<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use VsPoint\Entity\Locale\Locale;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;

interface GetLocaleById
{
  /**
   * @throws LocaleNotFoundById
   */
  public function __invoke(string $id): Locale;
}
