<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Entity\Locale\Locale;

use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Locale\Locale\LocaleORM;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Locale\Locale
 */
final class LocaleTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $container = $this->createContainer();
    $localeORM = $container->getByType(LocaleORM::class);

    $locale = new Locale(LocaleFixture::SLO, $localeORM);
    $locale2 = Locale::of(LocaleFixture::SLO);

    self::assertSame(LocaleFixture::SLO, $locale->getId());
    self::assertSame(LocaleFixture::SLO, $locale->__toString());
    self::assertTrue($locale->isEqual($locale2));
  }
}
