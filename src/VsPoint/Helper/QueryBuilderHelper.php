<?php

declare(strict_types=1);

namespace VsPoint\Helper;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Nette\StaticClass;

final class QueryBuilderHelper
{
  use StaticClass;

  /**
   * @param literal-string      $alias
   * @param literal-string      $join
   * @param literal-string      $groupBy
   * @param literal-string      $rev
   * @param literal-string|null $joinAlias
   */
  public static function onlyLastFilter(
    QueryBuilder $qb,
    string $alias,
    string $join,
    string $groupBy,
    string $rev,
    ?string $joinAlias = null,
  ): QueryBuilder {
    $joinAlias ??= $alias . 'JOIN';

    return $qb
      ->leftJoin(
        $join,
        $joinAlias,
        Join::WITH,
        $qb->expr()->andX(
          $qb->expr()->eq("{$alias}.{$groupBy}", "{$joinAlias}.{$groupBy}"),
          $qb->expr()->lt("{$alias}.{$rev}", "{$joinAlias}.{$rev}")
        )
      )
      ->andWhere($qb->expr()->isNull("{$joinAlias}.{$groupBy}"))
    ;
  }

  /**
   * @param literal-string      $alias
   * @param literal-string      $join
   * @param literal-string      $groupBy
   * @param literal-string      $id
   * @param literal-string|null $joinAlias
   */
  public static function onlyLastFilterByMax(
    QueryBuilder $qb,
    string $alias,
    string $join,
    string $groupBy,
    string $id = 'id',
    ?string $joinAlias = null,
  ): QueryBuilder {
    $joinAlias ??= $alias . 'JOIN';

    $sub = $qb->getEntityManager()->createQueryBuilder();
    $sub
      ->select($qb->expr()->max("{$joinAlias}.{$id}"))
      ->from($join, $joinAlias)
      ->groupBy("{$joinAlias}.{$groupBy}")
    ;

    return $qb
      ->andWhere($qb->expr()->in("{$alias}.{$id}", $sub->getDQL()))
    ;
  }

  /**
   * @param literal-string $class
   * @param literal-string $alias
   * @param array<string>  $ids
   */
  public static function createPostFetch(
    EntityManagerInterface $em,
    string $class,
    string $alias,
    array $ids,
  ): QueryBuilder {
    $qb = $em->createQueryBuilder();
    $qb
      ->select("partial {$alias}.{id}")
      ->from($class, $alias, "{$alias}.id")
      ->andWhere($qb->expr()->in("{$alias}.id", ':ids'))
      ->setParameter('ids', $ids, ArrayParameterType::STRING)
    ;

    return $qb;
  }
}
