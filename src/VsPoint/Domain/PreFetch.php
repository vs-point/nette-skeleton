<?php

declare(strict_types=1);

namespace VsPoint\Domain;

use loophp\collection\Contract\Collection;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\VO\PreFetch\PreFetchUserRoles;
use VsPoint\VO\PreFetch\PreFetchUsers;

interface PreFetch
{
  /**
   * @param Collection<int, User> $users
   */
  public function forUsers(Collection $users): PreFetchUsers;

  /**
   * @param Collection<int, UserRole> $userRoles
   */
  public function forUserRoles(Collection $userRoles): PreFetchUserRoles;
}
