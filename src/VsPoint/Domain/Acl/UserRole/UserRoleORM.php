<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\UserRole;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Entity\Acl\UserRole;

final readonly class UserRoleORM implements UserRoleCreated
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(UserRole $userRole): void
  {
    $this->em->persist($userRole);
  }
}
