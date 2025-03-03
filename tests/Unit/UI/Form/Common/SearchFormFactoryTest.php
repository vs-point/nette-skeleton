<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\UI\Form\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;
use VsPoint\UI\Form\Common\SearchFormFactory;

#[CoversClass(SearchFormFactory::class)]
final class SearchFormFactoryTest extends TestCase
{
  #[Group('unit')]
  public function testFactoryCreate(): void
  {
    $container = $this->createContainerForWeb();

    $factory = $container->getByType(SearchFormFactory::class);
    $form = $factory->create('placeholder');

    self::assertCount(3, $form->getComponents());
  }
}
