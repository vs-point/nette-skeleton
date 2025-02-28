<?php

declare(strict_types=1);

namespace Unit\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Acl\User\UserORM;
use VsPoint\Domain\Locale\Locale\LocaleORM;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Acl\User\UserORM
 */
final class LocaleORMTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $this->expectNotToPerformAssertions();

    $container = $this->createContainer();

    $em = $container->getByType(EntityManagerInterface::class);

    $localeORM = new LocaleORM($em);
  }

  /**
   * @group unit
   */
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
