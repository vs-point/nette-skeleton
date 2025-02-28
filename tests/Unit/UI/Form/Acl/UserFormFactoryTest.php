<?php

declare(strict_types=1);

namespace Unit\UI\Form\Acl;

use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Acl\UserFormFactory;

/**
 * @covers \VsPoint\UI\Form\Acl\UserFormFactory
 */
final class UserFormFactoryTest extends TestCase
{
  /**
   * @group unit
   */
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->create();

    self::assertCount(6, $form->getComponents());
  }

  /**
   * @group unit
   */
  public function testFactoryCreateEditPassword(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->createEditPassword();

    self::assertCount(4, $form->getComponents());
  }

  /**
   * @group unit
   */
  public function testFactoryCreatePasswordChange(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->createPasswordChange();

    self::assertCount(5, $form->getComponents());
  }
}
