<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use VsPoint\Entity\Acl\User;

interface DoesUserExist
{
  public function __invoke(string $email, ?User $user = null): bool;
}
