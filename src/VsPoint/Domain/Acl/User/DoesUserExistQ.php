<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as DoctrineNonUniqueResultException;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Logic\NonUniqueResultException;

final readonly class DoesUserExistQ implements DoesUserExist
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(string $email, ?User $user = null): bool
  {
    $qb = $this->em->createQueryBuilder();
    $qb
      ->select('user')
      ->from(User::class, 'user')
      ->where('user.email = :email')
      ->setParameter('email', $email)
    ;

    if ($user !== null) {
      $qb
        ->andWhere('user.id != :id')
        ->setParameter('id', $user->getId())
      ;
    }

    try {
      $user = $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);

      return $user !== null;
    } catch (DoctrineNonUniqueResultException $e) {
      throw NonUniqueResultException::from($e);
    }
  }
}
