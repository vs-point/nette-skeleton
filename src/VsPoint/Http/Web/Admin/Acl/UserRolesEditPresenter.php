<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleted;
use VsPoint\DTO\Acl\UserRolesFormDTO;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\Admin\BasePresenter;
use VsPoint\UI\Form\Acl\UserRolesFormFactory;

use function sprintf;

final class UserRolesEditPresenter extends BasePresenter
{
  public const string LINK = ':Admin:Acl:UserRolesEdit:';

  private User $aclUser;

  public function __construct(
    private readonly UserRolesFormFactory $userRolesFormFactory,
    private readonly UserRoleCreated $userRoleCreated,
    private readonly UserRoleDeleted $userRoleDeleted,
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
    /** @var Form $form */
    $form = $this->getComponent('userRolesForm');
    $form->setDefaults([
      'roles' => $this->aclUser->getUserRolesList(),
    ]);

    $template = $this->createTemplate(UserRolesEditTemplate::class);
    $template->setFile(__DIR__ . '/UserRolesEdit.latte');
    $template->aclUser = $this->aclUser;

    $response = new TextResponse($template);
    $this->sendResponse($response);
  }

  protected function createComponentUserRolesForm(): Form
  {
    $form = $this->userRolesFormFactory->create();

    $form->onSuccess[] = function (Form $form, UserRolesFormDTO $data): void {
      $this->em->wrapInTransaction(function () use ($data): void {
        $this->aclUser->editUserRoles($data->roles, $this->userRoleCreated, $this->userRoleDeleted);
      });

      $this->flashMessage(sprintf('admin.%s.flash.userRolesEdited', self::class), 'success');
      $this->redirect(UserOverviewPresenter::LINK);
    };

    return $form;
  }
}
