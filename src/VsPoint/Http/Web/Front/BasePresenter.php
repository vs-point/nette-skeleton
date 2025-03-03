<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use Override;
use VsPoint\Http\Web\BasePresenter as BBasePresenter;

abstract class BasePresenter extends BBasePresenter
{
  #[Override]
  public function findLayoutTemplateFile(): ?string
  {
    return __DIR__ . '/@template/@layout.latte';
  }
}
