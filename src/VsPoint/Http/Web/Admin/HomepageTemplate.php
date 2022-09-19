<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use Brick\DateTime\ZonedDateTime;
use Symfony\Component\Stopwatch\Stopwatch;

use VsPoint\Entity\Locale\Locale;

final class HomepageTemplate extends BaseTemplate
{
  public ZonedDateTime $timestamp;

  public string $q;

  public Locale $locale;

  public Stopwatch $stopwatch;
}
