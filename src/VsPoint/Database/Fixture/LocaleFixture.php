<?php

declare(strict_types=1);

namespace VsPoint\Database\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use VsPoint\Domain\Locale\Locale\LocaleCreated;
use VsPoint\Entity\Locale\Locale;

final class LocaleFixture extends AbstractFixture implements DependentFixtureInterface
{
  public const string ENG = 'eng';

  public const string CZE = 'cze';

  public const string SLO = 'slo';

  public function __construct(
    private readonly LocaleCreated $localeCreated,
  ) {
  }

  public function getDependencies(): array
  {
    return [InitFixture::class];
  }

  public function load(ObjectManager $manager): void
  {
    new Locale('eng', $this->localeCreated);
    new Locale('cze', $this->localeCreated);

    $manager->flush();
  }
}
