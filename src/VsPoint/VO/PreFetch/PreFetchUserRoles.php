<?php

declare(strict_types=1);

namespace VsPoint\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Sequence;
use Ds\Set;
use Ds\Vector;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Helper\Transform;

final class PreFetchUserRoles
{
  /**
   * @param Sequence<UserRole> $userRoles
   */
  public function __construct(
    private readonly EntityManagerInterface $em,
    private readonly Sequence $userRoles,
  ) {
  }

  /**
   * @return UserRole[]
   */
  public function toArray(): array
  {
    return $this->userRoles->toArray();
  }

  /**
   * @return Sequence<UserRole>
   */
  public function toSequence(): Sequence
  {
    return $this->userRoles;
  }

  /**
   * @param Set<UuidInterface> $ids
   */
  public static function byIds(EntityManagerInterface $em, Set $ids): self
  {
    /** @var UserRole[] $userRoles */
    $userRoles = $em
      ->createQuery(
        <<<'DQL'
        SELECT userRole
        FROM VsPoint\Entity\Acl\UserRole AS userRole
        WHERE userRole.id IN (:ids)
        DQL
      )
      ->setParameter('ids', Transform::fromUuidsToStringUuids($ids->toArray()))
      ->getResult()
    ;

    return new self($em, new Vector($userRoles));
  }

  public function withUser(): PreFetchUsers
  {
    /** @var Set<UuidInterface> $ids */
    $ids = $this->toSequence()->reduce(
      static function (Set $acc, UserRole $userRole): Set {
        $id = $userRole->getUser()->getId();
        $acc->add($id);

        return $acc;
      },
      new Set()
    );

    return PreFetchUsers::byIds($this->em, $ids);
  }
}
