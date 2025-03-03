<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Acl;

use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Acl\UserRolesFormFactory;

/**
 * @covers \VsPoint\UI\Form\Acl\UserRolesFormFactory
 */
final class UserRolesFormFactoryTest extends TestCase
{
  /**
   * @group unit
   */
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserRolesFormFactory::class);
    $form = $factory->create();

    self::assertCount(3, $form->getComponents());
  }
}
