<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\UserRole;

use VsPoint\Entity\Acl\UserRole;

interface UserRoleDeleted
{
  public function __invoke(UserRole $userRole): void;
}
