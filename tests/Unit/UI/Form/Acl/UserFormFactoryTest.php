<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Acl\UserFormFactory;

#[CoversClass(UserFormFactory::class)]
final class UserFormFactoryTest extends TestCase
{
  #[Group('unit')]
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->create();

    self::assertCount(6, $form->getComponents());
  }

  #[Group('unit')]
  public function testFactoryCreateEditPassword(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->createEditPassword();

    self::assertCount(4, $form->getComponents());
  }

  #[Group('unit')]
  public function testFactoryCreatePasswordChange(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserFormFactory::class);
    $form = $factory->createPasswordChange();

    self::assertCount(5, $form->getComponents());
  }
}
