<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Ds\Sequence;
use VsPoint\Entity\Locale\Locale;

interface FindLocales
{
  /**
   * @return Sequence<Locale>
   */
  public function __invoke(int $limit, int $offset): Sequence;
}
