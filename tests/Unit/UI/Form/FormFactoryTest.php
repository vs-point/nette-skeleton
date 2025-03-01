<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form;

use stdClass;
use Contributte\FormsBootstrap\BootstrapForm;
use Contributte\FormsBootstrap\Enums\RenderMode;
use Nette\Localization\Translator;
use VsPoint\Test\TestCase;
use VsPoint\UI\Form\FormFactory;

/**
 * @covers \VsPoint\UI\Form\FormFactory
 */
final class FormFactoryTest extends TestCase
{
  /**
   * @group unit
   */
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();
    $translator = $container->getByType(Translator::class);

    $factory = new FormFactory($translator);
    $form = $factory->create(stdClass::class);

    self::assertInstanceOf(BootstrapForm::class, $form);
    self::assertSame(RenderMode::VERTICAL_MODE, $form->getRenderMode());
    self::assertSame($translator, $form->getTranslator());
    self::assertFalse($form->isAjax());
  }
}
