<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use VsPoint\Domain\Acl\User\UserEdited;
use VsPoint\DTO\Acl\PasswordFormDTO;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\UI\Form\Acl\UserFormFactory;

use function sprintf;

final class UserEditPasswordPresenter extends BasePresenter
{
  public const LINK = ':Admin:Acl:UserEditPassword:';

  private User $aclUser;

  public function __construct(
    private readonly UserFormFactory $userFormFactory,
    private readonly UserEdited $userEdited,
    private readonly Passwords $passwords,
    private readonly EntityManagerInterface $em,
  ) {
    parent::__construct();
  }

  public function actionDefault(string $id): void
  {
    try {
      $uuid = Uuid::fromString($id);
      $this->aclUser = $this->getUserById->__invoke($uuid);
    } catch (InvalidUuidStringException | UserNotFound) {
      $this->flashMessage(sprintf('admin.%s.flash.userNotFound', self::class), 'error');
      $this->redirect(UserOverviewPresenter::LINK);
    }
  }

  public function renderDefault(string $id): void
  {
    $template = $this->createTemplate(UserEditPasswordTemplate::class);
    $template->setFile(__DIR__ . '/UserEditPassword.latte');
    $template->aclUser = $this->aclUser;

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentEditPasswordForm(): Form
  {
    $form = $this->userFormFactory->createEditPassword();

    $form->onSuccess[] = function (Form $form, PasswordFormDTO $data): void {
      $this->em->wrapInTransaction(function () use ($data): void {
        $this->aclUser->editPassword($data->password, $this->passwords, $this->userEdited);
      });

      $this->flashMessage(sprintf('admin.%s.flash.passwordChanged', self::class), 'success');
      $this->redirect(UserOverviewPresenter::LINK);
    };

    return $form;
  }
}
