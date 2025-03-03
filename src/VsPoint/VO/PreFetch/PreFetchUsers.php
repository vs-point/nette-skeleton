<?php

declare(strict_types=1);

namespace VsPoint\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use loophp\collection\Collection as LoopCollection;
use loophp\collection\Contract\Collection;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Helper\Transform;

final readonly class PreFetchUsers
{
  /**
   * @param Collection<int, User> $users
   */
  public function __construct(
    private EntityManagerInterface $em,
    private Collection $users,
  ) {
  }

  /**
   * @return User[]
   */
  public function toArray(): array
  {
    return $this->users->all();
  }

  /**
   * @return Collection<int, User>
   */
  public function toCollection(): Collection
  {
    return $this->users;
  }

  /**
   * @param Collection<int, UuidInterface> $ids
   */
  public static function byIds(EntityManagerInterface $em, Collection $ids): self
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
      ->setParameter('ids', Transform::fromUuidsToStringUuids($ids->all()))
      ->getResult()
    ;

    return new self($em, LoopCollection::fromIterable($users));
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

    /** @var Collection<int, UserRole> $userRoles */
    $userRoles = $this
      ->toCollection()
      ->reduce(
        static fn (Collection $acc, User $user): Collection => $user
          ->getUserRoles()
          ->reduce(
            static fn (Collection $acc, UserRole $userRole): Collection => $acc->append($userRole),
            $acc
          ),
        LoopCollection::empty()
      )
    ;

    return new PreFetchUserRoles($this->em, $userRoles);
  }
}
