<?php

declare(strict_types=1);

namespace VsPoint\Http\Web;

use Nette\Application\Attributes\Persistent;
use Nette\Application\Helpers;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Logic\InvalidStateException;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\Admin\Acl\SignInPresenter;
use VsPoint\Infrastructure\Nette\Security\Identity;

abstract class BasePresenter extends Presenter
{
  #[Persistent]
    public string $locale = 'cs';

  #[Persistent]
    public string $backlink = '';

  #[Inject]
    public GetUserById $getUserById;

  private ?User $loggedUser = null;

  /**
   * @return string[]
   */
  public function formatTemplateFiles(): array
  {
    /** @var string $name */
    $name = $this->getName();
    /** @var string $filename */
    $filename = self::getReflection()->getFileName();

    [, $presenter] = Helpers::splitName($name);
    $dir = dirname($filename);

    return [sprintf('%s/%s.latte', $dir, $presenter)];
  }

  public function isModuleCurrent(string $module): bool
  {
    return strpos($this->getAction(true), $module) !== false;
  }

  public function actionOut(): void
  {
    $this->getUser()->logout(true);

    $this->flashMessage(sprintf('app.%s.flash.acl.loggedOut', self::class));

    $this->redirect(SignInPresenter::LINK);
  }

  protected function getLoggedUser(): User
  {
    if (!$this->getUser()->isLoggedIn()) {
      throw new InvalidStateException(
        'User is not logged in - this method should be called only in areas where User is always logged!'
      );
    }

    if ($this->loggedUser !== null) {
      return $this->loggedUser;
    }

    /** @var Identity $identity */
    $identity = $this->getUser()->getIdentity();

    try {
      $this->loggedUser = $this->getUserById->__invoke($identity->getId());

      return $this->loggedUser;
    } catch (UserNotFound) {
      $this->getUser()->logout(true);
      $this->flashMessage(sprintf('app.%s.flash.acl.userNotFound', self::class), 'error');
      $this->redirect(SignInPresenter::LINK);
    }
  }

  protected function ajaxReload(string ...$snippets): void
  {
    if ($this->isAjax()) {
      foreach ($snippets as $snippet) {
        $this->redrawControl($snippet);
      }
      $this->payload->postGet = true;
      $this->payload->url = $this->link('this');
    } else {
      $this->redirect('this');
    }
  }
}
