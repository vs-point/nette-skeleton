<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Http\Web\Admin;

use Exception;
use Nette\Application\BadRequestException;
use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Bridges\ApplicationLatte\Template;
use VsPoint\Http\Web\Admin\ErrorPresenter;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Http\Web\Admin\ErrorPresenter
 */
final class ErrorPresenterTest extends TestCase
{
  /**
   * @group unit
   */
  public function testBadRequestConstructor(): void
  {
    $container = $this->createContainerForWeb();

    $presenterFactory = $container->getByType(IPresenterFactory::class);

    $presenterName = trim(ErrorPresenter::LINK, ':');

    $presenter = $presenterFactory->createPresenter($presenterName);

    self::assertInstanceOf(ErrorPresenter::class, $presenter);

    $presenter->autoCanonicalize = false;

    $request = new Request($presenterName, Request::FORWARD, [
      'action' => 'default',
      'exception' => new BadRequestException('Bad request', 404),
    ]);
    $response = $presenter->run($request);

    self::assertInstanceOf(TextResponse::class, $response);
    self::assertInstanceOf(Template::class, $response->getSource());
  }

  /**
   * @group unit
   */
  public function testExceptionConstructor(): void
  {
    $container = $this->createContainerForWeb();

    $presenterFactory = $container->getByType(IPresenterFactory::class);

    $presenterName = trim(ErrorPresenter::LINK, ':');

    $presenter = $presenterFactory->createPresenter($presenterName);

    self::assertInstanceOf(ErrorPresenter::class, $presenter);

    $presenter->autoCanonicalize = false;

    $request = new Request($presenterName, Request::FORWARD, [
      'action' => 'default',
      'exception' => new Exception('New exception', 500),
    ]);
    $response = $presenter->run($request);

    self::assertInstanceOf(TextResponse::class, $response);
    self::assertInstanceOf(Template::class, $response->getSource());
  }
}
