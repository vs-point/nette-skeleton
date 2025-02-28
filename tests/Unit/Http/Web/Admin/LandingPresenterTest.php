<?php

declare(strict_types=1);

namespace Unit\Http\Web\Admin;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Bridges\ApplicationLatte\Template;
use VsPoint\Http\Web\Admin\LandingPresenter;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Http\Web\Admin\LandingPresenter
 */
final class LandingPresenterTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $container = $this->createContainerForWeb();

    $presenterFactory = $container->getByType(IPresenterFactory::class);

    $presenterName = trim(LandingPresenter::LINK, ':');

    $presenter = $presenterFactory->createPresenter($presenterName);

    self::assertInstanceOf(LandingPresenter::class, $presenter);

    $presenter->autoCanonicalize = false;

    $request = new Request($presenterName, 'GET', [
      'action' => 'default',
    ]);
    $response = $presenter->run($request);

    self::assertInstanceOf(TextResponse::class, $response);
    self::assertInstanceOf(Template::class, $response->getSource());
  }
}
