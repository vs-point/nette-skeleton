<?php

declare(strict_types=1);

namespace VsPoint\VO\PreFetch;

use Doctrine\ORM\EntityManagerInterface;
use loophp\collection\Collection as LoopCollection;
use loophp\collection\Contract\Collection;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Helper\Transform;

final readonly class PreFetchUserRoles
{
  /**
   * @param Collection<int, UserRole> $userRoles
   */
  public function __construct(
    private EntityManagerInterface $em,
    private Collection $userRoles,
  ) {
  }

  /**
   * @return UserRole[]
   */
  public function toArray(): array
  {
    return $this->userRoles->all();
  }

  /**
   * @return Collection<int, UserRole>
   */
  public function toCollection(): Collection
  {
    return $this->userRoles;
  }

  /**
   * @param Collection<int, UuidInterface> $ids
   */
  public static function byIds(EntityManagerInterface $em, Collection $ids): self
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
      ->setParameter('ids', Transform::fromUuidsToStringUuids($ids->all()))
      ->getResult()
    ;

    return new self($em, LoopCollection::fromIterable($userRoles));
  }

  public function withUser(): PreFetchUsers
  {
    /** @var Collection<int, UuidInterface> $ids */
    $ids = $this->toCollection()->reduce(
      static function (Collection $acc, UserRole $userRole): Collection {
        $id = $userRole->getUser()->getId();

        return $acc->append($id);
      },
      LoopCollection::empty()
    );

    return PreFetchUsers::byIds($this->em, $ids);
  }
}
