<?php

declare(strict_types=1);

namespace VsPoint\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Sequence;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\VO\PreFetch\PreFetchUserRoles;
use VsPoint\VO\PreFetch\PreFetchUsers;

final class PreFetchQ implements PreFetch
{
  public function __construct(
    private readonly EntityManagerInterface $em,
  ) {
  }

  /**
   * @param Sequence<User> $users
   */
  public function forUsers(Sequence $users): PreFetchUsers
  {
    return new PreFetchUsers($this->em, $users);
  }

  /**
   * @param Sequence<UserRole> $userRoles
   */
  public function forUserRoles(Sequence $userRoles): PreFetchUserRoles
  {
    return new PreFetchUserRoles($this->em, $userRoles);
  }
}
