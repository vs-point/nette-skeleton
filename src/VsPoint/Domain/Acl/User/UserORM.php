<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Entity\Acl\User;

final readonly class UserORM implements UserCreated, UserEdited, UserLoggedIn
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(User $user): void
  {
    $this->em->persist($user);
  }
}
