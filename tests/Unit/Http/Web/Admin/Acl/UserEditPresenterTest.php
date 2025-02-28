<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Http\Web\Admin\Acl;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Bridges\ApplicationLatte\Template;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Http\Web\Admin\Acl\UserEditPresenter;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Http\Web\Admin\Acl\UserEditPresenter
 */
final class UserEditPresenterTest extends TestCase
{
  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    self::markTestSkipped('ToDo');

    $container = $this->createContainerForWeb();

    $presenterFactory = $container->getByType(IPresenterFactory::class);

    $presenterName = trim(UserEditPresenter::LINK, ':');

    $presenter = $presenterFactory->createPresenter($presenterName);

    self::assertInstanceOf(UserEditPresenter::class, $presenter);

    $presenter->autoCanonicalize = false;

    $request = new Request($presenterName, 'GET', [
      'action' => 'default',
      'id' => InitFixture::USER_01,
    ]);
    $response = $presenter->run($request);

    self::assertInstanceOf(TextResponse::class, $response);
    self::assertInstanceOf(Template::class, $response->getSource());
  }
}
