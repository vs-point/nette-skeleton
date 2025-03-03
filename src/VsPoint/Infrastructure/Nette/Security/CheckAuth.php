<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use Contributte\Events\Extra\Event\Application\PresenterStartupEvent;
use Solcik\Nette\Security\AclHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use VsPoint\Http\Web\Admin\Acl\SignInPresenter;
use VsPoint\Http\Web\Admin\LandingPresenter;
use VsPoint\Http\Web\BasePresenter;

final readonly class CheckAuth implements EventSubscriberInterface
{
  public function __construct(
    private AclHelper $aclHelper,
  ) {
  }

  /**
   * @return array<string, string>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      PresenterStartupEvent::class => 'onStartup',
    ];
  }

  public function onStartup(PresenterStartupEvent $event): void
  {
    $presenter = $event->getPresenter();

    if (!$presenter instanceof BasePresenter) {
      return;
    }

    $privilegeFlag = $this->aclHelper->checkPrivileges($presenter);

    if (($privilegeFlag & AclHelper::INACTIVITY) > 0) {
      $presenter->getUser()->logout(true)
      ;
      $presenter->flashMessage(sprintf('common.%s.flash.inactivity', self::class), 'warning');
    }

    if (($privilegeFlag & AclHelper::LOGIN_REQUIRED) > 0) {
      $presenter->flashMessage(sprintf('common.%s.flash.notLoggedIn', self::class), 'danger');
      $presenter
        ->getUser()
        ->logout(true)
      ;

      $presenter->backlink = $presenter->storeRequest();
      $presenter->redirect(SignInPresenter::LINK);
    }

    if (($privilegeFlag & AclHelper::NOT_ALLOWED) > 0) {
      $presenter->flashMessage(sprintf('common.%s.flash.notEnoughPermissions', self::class), 'danger');
      $presenter->redirect(LandingPresenter::LINK);
    }

    $presenter->user->onLoggedIn[] = static function () use ($presenter): void {
      $presenter->restoreRequest($presenter->backlink);
    };
  }
}
