<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Security\Passwords;
use VsPoint\Domain\Acl\User\UserEdited;
use VsPoint\DTO\Acl\PasswordWithCheckFormDTO;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\Http\Web\Admin\HomepagePresenter;
use VsPoint\UI\Form\Acl\UserFormFactory;

final class ChangePasswordPresenter extends BasePresenter
{
  public const LINK = ':Admin:Acl:ChangePassword:';

  public function __construct(
    private readonly UserFormFactory $userFormFactory,
    private readonly UserEdited $userEdited,
    private readonly Passwords $passwords,
    private readonly EntityManagerInterface $em,
  ) {
    parent::__construct();
  }

  public function renderDefault(): void
  {
    $template = $this->createTemplate(ChangePasswordTemplate::class);
    $template->setFile(__DIR__ . '/ChangePassword.latte');

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentEditPasswordForm(): Form
  {
    $form = $this->userFormFactory->createPasswordChange();

    $form->onSuccess[] = function (Form $form, PasswordWithCheckFormDTO $data): void {
      $t = 'admin.' . UserFormFactory::class;
      $user = $this->getLoggedUser();

      if (!$user->isPasswordCorrect($data->currentPassword, $this->passwords)) {
        /** @var BaseControl $formEmail */
        $formEmail = $form['currentPassword'];
        $formEmail->addError("${t}.currentPassword.error.incorrectPassword");

        return;
      }

      $this->em->wrapInTransaction(function () use ($user, $data): void {
        $user->editPassword($data->password, $this->passwords, $this->userEdited);
      });

      $this->flashMessage(sprintf('admin.%s.flash.passwordChanged', self::class), 'success');
      $this->redirect(HomepagePresenter::LINK);
    };

    return $form;
  }
}
