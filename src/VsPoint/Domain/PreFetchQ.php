<?php

declare(strict_types=1);

namespace VsPoint\Domain;

use Doctrine\ORM\EntityManagerInterface;
use loophp\collection\Contract\Collection;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\VO\PreFetch\PreFetchUserRoles;
use VsPoint\VO\PreFetch\PreFetchUsers;

final readonly class PreFetchQ implements PreFetch
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  /**
   * @param Collection<int, User> $users
   */
  public function forUsers(Collection $users): PreFetchUsers
  {
    return new PreFetchUsers($this->em, $users);
  }

  /**
   * @param Collection<int, UserRole> $userRoles
   */
  public function forUserRoles(Collection $userRoles): PreFetchUserRoles
  {
    return new PreFetchUserRoles($this->em, $userRoles);
  }
}
