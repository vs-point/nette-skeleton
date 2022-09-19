<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use Nette\Application\BadRequestException;
use Throwable;
use VsPoint\VO\Html\HtmlVO;

final class ErrorPresenter extends BasePresenter
{
  public const LINK = ':Front:Error:';

  public function __construct()
  {
    parent::__construct();
  }

  public function renderDefault(Throwable $exception): void
  {
    $folder = __DIR__ . '/@template/@error';
    $status = 500;

    if ($exception instanceof BadRequestException) {
      $status = $exception->getHttpCode();
      $file = sprintf('%s/%s.latte', $folder, $exception->getCode());
      $file = is_file($file) ? $file : sprintf('%s/4xx.latte', $folder);
    } else {
      $file = sprintf('%s/500.latte', $folder);
    }

    $meta = HtmlVO::create((string) $status, (string) $status, [], sprintf('Error%s', $status));

    $template = $this->createTemplate(ErrorTemplate::class);
    $template->setFile($file);
    $template->meta = $meta;
    $this->sendTemplate($template);
  }
}
