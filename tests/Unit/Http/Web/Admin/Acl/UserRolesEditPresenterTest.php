<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Http\Web\Admin\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Bridges\ApplicationLatte\Template;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Http\Web\Admin\Acl\UserRolesEditPresenter;
use VsPoint\Test\TestCase;

#[CoversClass(UserRolesEditPresenter::class)]
final class UserRolesEditPresenterTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $container = $this->createContainerForWeb();

    $presenterFactory = $container->getByType(IPresenterFactory::class);

    $presenterName = trim(UserRolesEditPresenter::LINK, ':');

    $presenter = $presenterFactory->createPresenter($presenterName);

    self::assertInstanceOf(UserRolesEditPresenter::class, $presenter);

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
