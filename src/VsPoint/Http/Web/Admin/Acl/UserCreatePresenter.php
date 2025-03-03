<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Brick\DateTime\LocalTime;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Localization\Translator;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\DTO\Acl\UserFormDTO;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\UI\Form\Acl\UserFormFactory;

final class UserCreatePresenter extends BasePresenter
{
  public const string LINK = ':Admin:Acl:UserCreate:';

  public function __construct(
    private readonly UserFormFactory $userFormFactory,
    private readonly Clock $clock,
    private readonly DoesUserExist $doesUserExist,
    private readonly Passwords $passwords,
    private readonly UserCreated $userCreated,
    private readonly Translator $trans,
    private readonly EntityManagerInterface $em,
  ) {
    parent::__construct();
  }

  public function renderDefault(): void
  {
    $template = $this->createTemplate(UserCreateTemplate::class);
    $template->setFile(__DIR__ . '/UserCreate.latte');

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentUserCreateForm(): Form
  {
    $form = $this->userFormFactory->create();
    unset($form['expiration']);

    $form->onSuccess[] = function (Form $form, UserFormDTO $data): void {
      $t = 'admin.' . UserFormFactory::class;

      try {
        $this->em->wrapInTransaction(function () use ($data): void {
          $timestamp = $this->clock->createZonedDateTime();
          $expiration = $data->expiration?->atTime(LocalTime::max())->atTimeZone($timestamp->getTimeZone());

          User::create(
            Uuid::uuid4(),
            $data->email,
            $data->password,
            $expiration,
            $timestamp,
            $timestamp->plusYears(2),
            $this->doesUserExist,
            $this->passwords,
            $this->userCreated
          );
        });
      } catch (UserAlreadyExistsException) {
        /** @var BaseControl $formEmail */
        $formEmail = $form['email'];
        $formEmail->addError($this->trans->translate("{$t}.email.error.alreadyExists"));

        return;
      }

      $this->flashMessage(sprintf('admin.%s.flash.userCreated', self::class), 'success');
      $this->redirect(SignInPresenter::LINK);
    };

    return $form;
  }
}
