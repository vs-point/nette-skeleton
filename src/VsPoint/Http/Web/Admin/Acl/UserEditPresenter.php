<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Brick\DateTime\LocalTime;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\SubmitButton;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserEdited;
use VsPoint\DTO\Acl\UserFormDTO;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\UI\Form\Acl\UserFormFactory;

use function sprintf;

final class UserEditPresenter extends BasePresenter
{
  public const LINK = ':Admin:Acl:UserEdit:';

  private User $aclUser;

  public function __construct(
    private readonly UserFormFactory $userFormFactory,
    private readonly DoesUserExist $doesUserExist,
    private readonly UserEdited $userEdited,
    private readonly Clock $clock,
    private readonly EntityManagerInterface $em,
  ) {
    parent::__construct();
  }

  public function actionDefault(string $id): void
  {
    try {
      $uuid = Uuid::fromString($id);
      $this->aclUser = $this->getUserById->__invoke($uuid);
    } catch (InvalidUuidStringException|UserNotFound) {
      $this->flashMessage(sprintf('admin.%s.flash.userNotFound', self::class), 'error');
      $this->redirect(UserOverviewPresenter::LINK);
    }
  }

  public function renderDefault(string $id): void
  {
    $user = $this->aclUser;

    /** @var Form $form */
    $form = $this->getComponent('userForm');
    $form->setDefaults([
      'email' => $user->getEmail(),
      'expiration' => $user->getExpiration()?->getDate(),
    ]);

    $template = $this->createTemplate(UserEditTemplate::class);
    $template->setFile(__DIR__ . '/UserEdit.latte');
    $template->aclUser = $this->aclUser;

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentUserForm(): Form
  {
    $form = $this->userFormFactory->create($this->aclUser);
    /** @var SubmitButton $submit */
    $submit = $form['submit'];
    $submit->caption = 'admin.form.label.edit';

    $form->onSuccess[] = function (Form $form, UserFormDTO $data): void {
      $t = 'admin.' . UserFormFactory::class;

      try {
        $this->em->wrapInTransaction(function () use ($data): void {
          $timestamp = $this->clock->createZonedDateTime();
          $expiration = $data->expiration?->atTime(LocalTime::max())->atTimeZone($timestamp->getTimeZone());

          $this->aclUser->edit($data->email, $expiration, $this->doesUserExist, $this->userEdited);
        });
      } catch (UserAlreadyExistsException) {
        /** @var BaseControl $formEmail */
        $formEmail = $form['email'];
        $formEmail->addError("${t}.email.error.alreadyExists");

        return;
      }

      $this->flashMessage(sprintf('admin.%s.flash.userEdited', self::class), 'success');
      $this->redirect(UserOverviewPresenter::LINK);
    };

    return $form;
  }
}
