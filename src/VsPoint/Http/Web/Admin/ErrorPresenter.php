<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Throwable;

final class ErrorPresenter extends BasePresenter
{
  public const LINK = ':Admin:Error:';

  /**
   * @throw BadRequestException
   */
  public function startup(): void
  {
    parent::startup();

    $request = $this->getRequest();
    if ($request !== null && !$request->isMethod(Request::FORWARD)) {
      throw new BadRequestException();
    }
  }

  public function renderDefault(Throwable $exception): void
  {
    $folder = __DIR__ . '/@template/@error';

    if ($exception instanceof BadRequestException) {
      $file = sprintf('%s/%s.latte', $folder, $exception->getCode());
      $file = is_file($file) ? $file : sprintf('%s/4xx.latte', $folder);
    } else {
      $file = sprintf('%s/500.latte', $folder);
    }

    $template = $this->createTemplate(ErrorTemplate::class);
    $template->setFile($file);
    $this->sendTemplate($template);
  }
}
