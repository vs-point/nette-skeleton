<?php

declare(strict_types=1);

namespace VsPoint\UI\Form\Acl;

use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Nette\SmartObject;
use VsPoint\DTO\Acl\UserRolesFormDTO;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\UI\Form\FormFactory;

final readonly class UserRolesFormFactory
{
  use SmartObject;

  public function __construct(
    private FormFactory $formFactory,
    private Translator $trans,
  ) {
  }

  public function create(): Form
  {
    $form = $this->formFactory->create(UserRolesFormDTO::class);
    $t = 'admin.' . self::class;

    $roles = array_map(
      fn (string $item): string => $this->trans->translate(
        sprintf('admin.%s.roles.values.%s', UserRole::class, $item)
      ),
      Role::getAllRoles()
    );

    $form
      ->addCheckboxList($f = 'roles', "{$t}.{$f}.label", $roles)
      ->setTranslator(null)
        ;

    $form
      ->addSubmit($f = 'submit', 'admin.form.label.submit');

    return $form;
  }
}
