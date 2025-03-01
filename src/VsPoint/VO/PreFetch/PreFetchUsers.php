<?php

declare(strict_types=1);

namespace VsPoint\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Map;
use Ds\Sequence;
use Ds\Set;
use Ds\Vector;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Helper\Transform;

final readonly class PreFetchUsers
{
  /**
   * @param Sequence<User> $users
   */
  public function __construct(
    private EntityManagerInterface $em,
    private Sequence $users,
  ) {
  }

  /**
   * @return User[]
   */
  public function toArray(): array
  {
    return $this->users->toArray();
  }

  /**
   * @return Sequence<User>
   */
  public function toSequence(): Sequence
  {
    return $this->users;
  }

  /**
   * @param Set<UuidInterface> $ids
   */
  public static function byIds(EntityManagerInterface $em, Set $ids): self
  {
    /** @var User[] $users */
    $users = $em
      ->createQuery(
        <<<'DQL'
        SELECT user
        FROM VsPoint\Entity\Acl\User AS user
        WHERE user.id IN (:ids)
        DQL
      )
      ->setParameter('ids', Transform::fromUuidsToStringUuids($ids->toArray()))
      ->getResult()
    ;

    return new self($em, new Vector($users));
  }

  public function withUserRoles(): PreFetchUserRoles
  {
    $this->em
      ->createQuery(
        <<<'DQL'
        SELECT PARTIAL user.{id}, userRole
        FROM VsPoint\Entity\Acl\User AS user
        LEFT JOIN user.userRoles userRole
        WHERE user.id IN (:ids)
        DQL
      )
      ->setParameter('ids', Transform::fromEntitiesToStringUuids($this->toArray()))
      ->getResult()
    ;

    /** @var Map<UuidInterface, UserRole> $userRoles */
    $userRoles = $this
      ->toSequence()
      ->reduce(
        static fn(Map $acc, User $user): Map => $user
          ->getUserRoles()
          ->reduce(
            static function (Map $acc, UserRole $userRole): Map {
              $acc->put($userRole->getId(), $userRole);

              return $acc;
            },
            $acc
          ),
        new Map()
      )
    ;

    return new PreFetchUserRoles($this->em, $userRoles->values());
  }
}
