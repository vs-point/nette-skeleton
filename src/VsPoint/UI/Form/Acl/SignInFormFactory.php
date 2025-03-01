<?php

declare(strict_types=1);

namespace VsPoint\UI\Form\Acl;

use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use VsPoint\DTO\Acl\SignInFormDTO;
use VsPoint\UI\Form\FormFactory;

final readonly class SignInFormFactory
{
  use SmartObject;

  public function __construct(
    private FormFactory $formFactory,
  ) {
  }

  public function create(): Form
  {
    /** @var BootstrapForm $form */
    $form = $this->formFactory->create(SignInFormDTO::class);
    $form->setShowValidation(false);
    $form->setAutoShowValidation(false);

    $t = 'admin.' . self::class;

    $form
      ->addText($f = 'email', "{$t}.{$f}.label")
      ->setRequired('admin.form.rule.required')
        ;

    $form
      ->addPassword($f = 'password', "{$t}.{$f}.label")
      ->setRequired('admin.form.rule.required')
        ;

    $form
      ->addSubmit($f = 'submit', "{$t}.{$f}.caption");

    return $form;
  }
}
