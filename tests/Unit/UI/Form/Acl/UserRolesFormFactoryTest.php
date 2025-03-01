<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Acl;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Acl\UserRolesFormFactory;

#[CoversClass(UserRolesFormFactory::class)]
final class UserRolesFormFactoryTest extends TestCase
{
  #[Group('unit')]
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(UserRolesFormFactory::class);
    $form = $factory->create();

    self::assertCount(3, $form->getComponents());
  }
}
