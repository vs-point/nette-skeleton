<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use Nette\DI\Attributes\Inject;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Logic\InvalidStateException;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\Admin\Acl\SignInPresenter;
use VsPoint\Http\Web\BasePresenter as BBasePresenter;
use VsPoint\Infrastructure\Nette\Security\Identity;

abstract class BasePresenter extends BBasePresenter
{
  #[Inject]
    public GetUserById $getUserById;

  protected ?User $loggedUser = null;

  public function findLayoutTemplateFile(): ?string
  {
    return __DIR__ . '/@template/@layout.latte';
  }

  public function actionOut(): void
  {
    $this->getUser()->logout(true);

    $this->flashMessage(sprintf('admin.%s.flash.acl.loggedOut', self::class));

    $this->redirect(SignInPresenter::LINK);
  }

  protected function getLoggedUser(): User
  {
    if (!$this->getUser()->isLoggedIn()) {
      throw new InvalidStateException(
        'User is not logged in - this method should be called only in areas where User is always logged!'
      );
    }
    if ($this->loggedUser === null) {
      /** @var Identity $identity */
      $identity = $this->getUser()->getIdentity();
      try {
        $this->loggedUser = $this->getUserById->__invoke($identity->getId());
      } catch (UserNotFound) {
        $this->getUser()->logout(true);
        $this->flashMessage(sprintf('admin.%s.flash.acl.userNotFound', self::class), 'error');
        $this->redirect(SignInPresenter::LINK);
      }
    }

    return $this->loggedUser;
  }
}
