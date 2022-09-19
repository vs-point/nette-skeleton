<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use VsPoint\Http\Web\BasePresenter as BBasePresenter;

abstract class BasePresenter extends BBasePresenter
{
  public function findLayoutTemplateFile(): ?string
  {
    return __DIR__ . '/@template/@layout.latte';
  }
}
