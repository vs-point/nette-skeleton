<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Locale\Locale;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Locale\Locale\LocaleORM;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Test\TestCase;

#[CoversClass(LocaleORM::class)]
final class LocaleORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $localeORM = new LocaleORM($em);
  }

  #[Group('unit')]
  public function testInvoke(): void
  {
    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $locale = $em->find(Locale::class, LocaleFixture::CZE);
    self::assertInstanceOf(Locale::class, $locale);

    $emMock = Mockery::mock(EntityManagerInterface::class);
    $emMock->allows('persist')->once();

    $userORM = new LocaleORM($emMock);

    $userORM->__invoke($locale);
  }
}
