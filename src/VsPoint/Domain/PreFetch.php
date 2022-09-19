<?php

declare(strict_types=1);

namespace VsPoint\Domain;

use Ds\Sequence;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\VO\PreFetch\PreFetchUserRoles;
use VsPoint\VO\PreFetch\PreFetchUsers;

interface PreFetch
{
  /**
   * @param Sequence<User> $users
   */
  public function forUsers(Sequence $users): PreFetchUsers;

  /**
   * @param Sequence<UserRole> $userRoles
   */
  public function forUserRoles(Sequence $userRoles): PreFetchUserRoles;
}
