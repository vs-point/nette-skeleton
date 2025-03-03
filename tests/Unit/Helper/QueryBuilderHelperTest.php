<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\ORM\QueryBuilder;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use VsPoint\Database\Fixture\InitFixture;
use VsPoint\Entity\Acl\User;
use VsPoint\Helper\QueryBuilderHelper;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Helper\QueryBuilderHelper
 */
final class QueryBuilderHelperTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  public function testOnlyLastFilter(): void
  {
    $container = $this->createContainer();
    $em = $container->getByType(EntityManagerInterface::class);

    $qb = new QueryBuilder($em);
    $qb
      ->select('user')
      ->from(User::class, 'user')
    ;

    $qbLast = QueryBuilderHelper::onlyLastFilter($qb, 'userRole', 'user.userRoles', 'userRole', 'id');

    $joinDql = $qbLast->getDQLPart('join');

    self::assertCount(1, $joinDql);
    self::assertArrayHasKey('user', $joinDql);
    self::assertCount(1, $joinDql['user']);
    self::assertInstanceOf(Join::class, $joinDql['user'][0]);
  }

  public function testOnlyLastFilterByMax(): void
  {
    $container = $this->createContainer();
    $em = $container->getByType(EntityManagerInterface::class);

    $qb = new QueryBuilder($em);
    $qb
      ->select('user')
      ->from(User::class, 'user')
    ;

    $qbLast = QueryBuilderHelper::onlyLastFilterByMax($qb, 'userRole', 'user.userRoles', 'userRole');

    $whereDqlParts = $qbLast->getDQLPart('where');

    self::assertInstanceOf(Andx::class, $whereDqlParts);
    self::assertCount(1, $whereDqlParts->getParts());
  }

  public function testCreatePostFetch(): void
  {
    $container = $this->createContainer();
    $em = $container->getByType(EntityManagerInterface::class);

    $ids = [InitFixture::USER_01];

    $qbPostFetch = QueryBuilderHelper::createPostFetch($em, User::class, 'user', $ids);

    $selectDqlPart = $qbPostFetch->getDQLPart('select');

    self::assertCount(1, $selectDqlPart);

    $selectPartial = $selectDqlPart[0];

    self::assertInstanceOf(Select::class, $selectPartial);
    self::assertSame('partial user.{id}', $selectPartial->__toString());
  }
}
