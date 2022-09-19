<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use VsPoint\Entity\Acl\User;

interface UserEdited
{
  public function __invoke(User $user): void;
}
