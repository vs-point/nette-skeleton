<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use Brick\DateTime\ZonedDateTime;

final class HomepageTemplate extends BaseTemplate
{
  public ZonedDateTime $timestamp;
}
