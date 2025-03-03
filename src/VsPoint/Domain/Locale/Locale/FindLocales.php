<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use loophp\collection\Contract\Collection;
use VsPoint\Entity\Locale\Locale;

interface FindLocales
{
  /**
   * @return Collection<int, Locale>
   */
  public function __invoke(int $limit, int $offset): Collection;
}
