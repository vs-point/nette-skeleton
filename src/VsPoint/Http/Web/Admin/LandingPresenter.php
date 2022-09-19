<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use Nette\Application\AbortException;
use Nette\Application\Responses\TextResponse;

final class LandingPresenter extends BasePresenter
{
  public const LINK = ':Admin:Landing:';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @throws AbortException
   */
  public function renderDefault(): void
  {
    $template = $this->createTemplate(LandingTemplate::class);
    $template->setFile(__DIR__ . '/Landing.latte');

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }
}
