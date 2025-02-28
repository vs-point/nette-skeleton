<?php

namespace Unit\Domain\Locale\Locale;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as ORMNonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Locale\Locale\GetLocaleById;
use VsPoint\Domain\Locale\Locale\GetLocaleByIdQ;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Locale\Locale\GetLocaleByIdQ
 */
class GetLocaleByIdQTest extends TestCase
{
  use MockeryPHPUnitIntegration;
  /**
   * @group unit
   */
  public function testGet(): void
  {
    $container = $this->createContainer();

    $getLocaleById = $container->getByType(GetLocaleByIdQ::class);
    $locale = $getLocaleById->__invoke(LocaleFixture::ENG);

    self::assertInstanceOf(Locale::class, $locale);
    self::assertSame(Locale::ENG, $locale->getId());
  }

  /**
   * @group unit
   */
  public function testNonUniqueResult(): void
  {
    $this->expectException(NonUniqueResultException::class);

    $emMock = $this->setMockery(new ORMNonUniqueResultException());

    $getLocaleById = new GetLocaleByIdQ($emMock);
    $getLocaleById->__invoke(LocaleFixture::ENG);
  }

  /**
   * @group unit
   */
  public function testNoResult(): void
  {
    $this->expectException(LocaleNotFoundById::class);

    $emMock = $this->setMockery(new NoResultException());

    $getLocaleById = new GetLocaleByIdQ($emMock);
    $getLocaleById->__invoke(LocaleFixture::ENG);
  }

  private function setMockery(\Exception $exception): EntityManagerInterface
  {
    $emMock = \Mockery::mock(EntityManagerInterface::class);

    $queryMock = \Mockery::mock(AbstractQuery::class);
    $queryMock
      ->allows('setParameter')->andReturnSelf()
    ;
    $queryMock
      ->allows('getSingleResult')
      ->andThrows($exception)
    ;

    $emMock
      ->allows('createQuery')
      ->andReturns($queryMock)
    ;

    return $emMock;
  }
}
