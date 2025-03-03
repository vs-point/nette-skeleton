<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Entity\Locale\Locale;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Locale\Locale\LocaleORM;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Test\TestCase;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
  #[Group('unit')]
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
