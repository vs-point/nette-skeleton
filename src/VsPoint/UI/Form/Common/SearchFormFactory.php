<?php

declare(strict_types=1);

namespace VsPoint\UI\Form\Common;

use Nette\Application\UI\Form;
use Nette\SmartObject;
use VsPoint\DTO\Common\SearchFormDTO;
use VsPoint\UI\Form\FormFactory;

final class SearchFormFactory
{
  use SmartObject;

  public function __construct(
    private FormFactory $formFactory,
  ) {
  }

  public function create(string $placeholder): Form
  {
    $form = $this->formFactory->create(SearchFormDTO::class);
    $t = 'front.' . self::class;

    $form
      ->addText($f = 'query', "{$t}.{$f}.label")
      ->setHtmlAttribute('placeholder', $placeholder)
      ->setHtmlAttribute('autofocus', true)
        ;

    $form
      ->addSubmit($f = 'submit', 'common.form.label.search');

    return $form;
  }
}
