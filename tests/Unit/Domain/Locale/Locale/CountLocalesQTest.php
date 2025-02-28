<?php

declare(strict_types=1);

namespace Unit\Domain\Locale\Locale;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Domain\Locale\Locale\CountLocalesQ;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Domain\Locale\Locale\CountLocalesQ
 */
class CountLocalesQTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testGet(): void
  {
    $container = $this->createContainer();

    $countLocalesQ = $container->getByType(CountLocalesQ::class);

    self::assertEquals(2, $countLocalesQ->__invoke());
  }

  /**
   * @group unit
   */
  public function testCatchNoResultException(): void
  {
    $emMock = $this->setMockery(new NoResultException());

    $countLocalesQ = new CountLocalesQ($emMock);

    self::assertEquals(0, $countLocalesQ->__invoke());
  }

  /**
   * @group unit
   */
  public function testCatchNonUniqueResultException(): void
  {
    $emMock = $this->setMockery(new NonUniqueResultException());

    $countLocalesQ = new CountLocalesQ($emMock);

    self::assertEquals(0, $countLocalesQ->__invoke());
  }

  private function setMockery(\Exception $exception): EntityManagerInterface
  {
    $emMock = \Mockery::mock(EntityManagerInterface::class);

    $queryMock = \Mockery::mock(AbstractQuery::class);
    $queryMock
      ->allows('setParameter')->andReturnSelf()
    ;
    $queryMock
      ->allows('getSingleScalarResult')
      ->andThrows($exception)
    ;

    $emMock
      ->allows('createQuery')
      ->andReturns($queryMock)
    ;

    return $emMock;
  }
}
