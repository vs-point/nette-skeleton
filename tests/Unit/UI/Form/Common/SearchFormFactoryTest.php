<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Common;

use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Common\SearchFormFactory;

/**
 * @covers \VsPoint\UI\Form\Common\SearchFormFactory
 */
final class SearchFormFactoryTest extends TestCase
{
  /**
   * @group unit
   */
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(SearchFormFactory::class);
    $form = $factory->create('placeholder');

    self::assertCount(3, $form->getComponents());
  }
}
