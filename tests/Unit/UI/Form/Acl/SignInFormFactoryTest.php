<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Acl;

use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Acl\SignInFormFactory;

/**
 * @covers \VsPoint\UI\Form\Acl\SignInFormFactory
 */
final class SignInFormFactoryTest extends TestCase
{
  /**
   * @group unit
   */
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(SignInFormFactory::class);
    $form = $factory->create();

    self::assertCount(4, $form->getComponents());
  }
}
