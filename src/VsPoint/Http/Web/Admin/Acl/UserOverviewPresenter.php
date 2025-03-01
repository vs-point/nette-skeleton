<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Nette\Application\Responses\TextResponse;
use VsPoint\Domain\Acl\User\FindUsers;
use VsPoint\Http\Web\Admin\BasePresenter;

final class UserOverviewPresenter extends BasePresenter
{
  public const string LINK = ':Admin:Acl:UserOverview:';

  public function __construct(
    private readonly FindUsers $findUsers,
        //        private readonly PreFetch $preFetch,
  ) {
    parent::__construct();
  }

  public function renderDefault(bool $activeOnly = true): void
  {
    $users = $this->findUsers->__invoke();
    //        $usersFC = $this->preFetch->fromUsers($users);
    //        $usersFC->withUserRoles();

    $template = $this->createTemplate(UserOverviewTemplate::class);
    $template->setFile(__DIR__ . '/UserOverview.latte');
    $template->users = $users;

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }
}
