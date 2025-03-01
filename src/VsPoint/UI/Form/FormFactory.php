<?php

declare(strict_types=1);

namespace VsPoint\UI\Form;

use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;

final class FormFactory
{
  public function __construct(
    private Translator $translator,
  ) {
  }

  /**
   * @param class-string $dataTransferObjectClass
   */
  public function create(string $dataTransferObjectClass): Form
  {
    $form = new BootstrapForm();
    $form->setRenderMode(RenderMode::VERTICAL_MODE);
    $form->setMappedType($dataTransferObjectClass);
    $form->setTranslator($this->translator);
    $form->addProtection('common.form.flash.invalidCsrf');
    $form->setAjax(false);

    $form->getElementPrototype()->addAttributes([
      'novalidate' => 'true',
    ])
        ;

    return $form;
  }
}
