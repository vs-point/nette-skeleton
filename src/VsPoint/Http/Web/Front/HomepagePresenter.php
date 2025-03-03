<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Front;

use Nette\Application\Responses\TextResponse;
use Solcik\Brick\DateTime\Clock;
use VsPoint\VO\Html\HtmlVO;

final class HomepagePresenter extends BasePresenter
{
  public const string LINK = ':Front:Homepage:';

  public function __construct(
    private readonly Clock $clock,
  ) {
    parent::__construct();
  }

  public function renderDefault(): void
  {
    $timestamp = $this->clock->createZonedDateTime();

    $meta = HtmlVO::create('Hlavní stránka', '', [], 'Homepage');

    $template = $this->createTemplate(HomepageTemplate::class);
    $template->setFile(__DIR__ . '/Homepage.latte');
    $template->meta = $meta;
    $template->timestamp = $timestamp;

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }
}
