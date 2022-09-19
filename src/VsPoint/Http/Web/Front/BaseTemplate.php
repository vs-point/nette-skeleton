<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use VsPoint\Http\Web\BaseTemplate as BBaseTemplate;
use VsPoint\VO\Html\HtmlVO;

abstract class BaseTemplate extends BBaseTemplate
{
  public HtmlVO $meta;
}
