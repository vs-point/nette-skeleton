<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Domain\Locale\Locale;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Exception;
use Mockery;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as ORMNonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\LocaleFixture;
use VsPoint\Domain\Locale\Locale\GetLocaleByIdQ;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;
use VsPoint\Test\TestCase;

#[CoversClass(GetLocaleByIdQ::class)]
class GetLocaleByIdQTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @throws LocaleNotFoundById
   */
  #[Group('unit')]
  public function testGet(): void
  {
    $container = $this->createContainer();

    $getLocaleById = $container->getByType(GetLocaleByIdQ::class);
    $locale = $getLocaleById->__invoke(LocaleFixture::ENG);

    self::assertSame(Locale::ENG, $locale->getId());
  }

  /**
   * @throws LocaleNotFoundById
   */
  #[Group('unit')]
  public function testNonUniqueResult(): void
  {
    $this->expectException(NonUniqueResultException::class);

    $emMock = $this->setMockery(new ORMNonUniqueResultException());

    $getLocaleById = new GetLocaleByIdQ($emMock);
    $getLocaleById->__invoke(LocaleFixture::ENG);
  }

  /**
   * @throws LocaleNotFoundById
   */
  #[Group('unit')]
  public function testNoResult(): void
  {
    $this->expectException(LocaleNotFoundById::class);

    $emMock = $this->setMockery(new NoResultException());

    $getLocaleById = new GetLocaleByIdQ($emMock);
    $getLocaleById->__invoke(LocaleFixture::ENG);
  }

  private function setMockery(Exception $exception): EntityManagerInterface
  {
    $emMock = Mockery::mock(EntityManagerInterface::class);

    $queryMock = Mockery::mock(AbstractQuery::class);
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
