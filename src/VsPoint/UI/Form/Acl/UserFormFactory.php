<?php

declare(strict_types=1);

namespace VsPoint\UI\Form\Acl;

use Contributte\Translation\Wrappers\Message;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Solcik\Nette\Forms\Controls\LocalDateInput;
use VsPoint\DTO\Acl\PasswordFormDTO;
use VsPoint\DTO\Acl\PasswordWithCheckFormDTO;
use VsPoint\DTO\Acl\UserFormDTO;
use VsPoint\Entity\Acl\User;
use VsPoint\UI\Form\FormFactory;

final class UserFormFactory
{
  use SmartObject;

  /**
   * @var int
   */
  private const PASSWORD_MIN_LENGTH = 8;

  public function __construct(
    private readonly FormFactory $formFactory,
  ) {
  }

  public function create(?User $user = null): Form
  {
    $form = $this->formFactory->create(UserFormDTO::class);
    $t = 'admin.' . self::class;

    $form
      ->addEmail($f = 'email', "${t}.${f}.label")
      ->setRequired('admin.form.rule.required')
      ->setDefaultValue('@')
        ;

    if ($user === null) {
      $this->addPasswordElements($form);
    }

    $f = 'expiration';
    $control = new LocalDateInput("${t}.${f}.label");
    $form->addComponent($control, $f);

    $form
      ->addSubmit($f = 'submit', "${t}.${f}.caption");

    return $form;
  }

  /**
   * This form is intended for changing passwords of other users
   *  - it is necessary check that user has appropriate permissions to changes other users passwords
   */
  public function createEditPassword(): Form
  {
    $form = $this->formFactory->create(PasswordFormDTO::class);
    $t = 'admin.' . self::class;

    $this->addPasswordElements($form);

    $form
      ->addSubmit($f = 'submitChange', "${t}.${f}.caption");

    return $form;
  }

  /**
   * This form is intended for user's own password
   *  - user should be able to changes password only to himself
   */
  public function createPasswordChange(): Form
  {
    $form = $this->formFactory->create(PasswordWithCheckFormDTO::class);
    $t = 'admin.' . self::class;

    $form
      ->addPassword($f = 'currentPassword', "${t}.${f}.label")
      ->setRequired('admin.form.rule.required')
        ;

    $this->addPasswordElements($form);

    $form
      ->addSubmit($f = 'submitChange', "${t}.${f}.caption");

    return $form;
  }

  private function addPasswordElements(Form $form): Form
  {
    $t = 'admin.' . self::class;

    $form
      ->addPassword($f = 'password', "${t}.${f}.label")
      ->setRequired('admin.form.rule.required')
      ->setOption('description', new Message("${t}.${f}.length", [
        'length' => self::PASSWORD_MIN_LENGTH,
      ]))
      ->addRule(
        $form::MIN_LENGTH,
        new Message(
          "${t}.${f}.length",
          [
            'length' => self::PASSWORD_MIN_LENGTH,
          ]
        ),
        self::PASSWORD_MIN_LENGTH
      )
        ;

    $form
      ->addPassword($f = 'passwordCheck', "${t}.${f}.label")
      ->addRule(Form::EQUAL, "${t}.${f}.error.passwordEqualCheck", $form['password'])
      ->setRequired('admin.form.rule.required')
      ->setOption('description', new Message("${t}.${f}.length", [
        'length' => self::PASSWORD_MIN_LENGTH,
      ]))
      ->addRule(
        $form::MIN_LENGTH,
        new Message(
          "${t}.${f}.length",
          [
            'length' => self::PASSWORD_MIN_LENGTH,
          ]
        ),
        self::PASSWORD_MIN_LENGTH
      )
      ->setOmitted()
        ;

    return $form;
  }
}
