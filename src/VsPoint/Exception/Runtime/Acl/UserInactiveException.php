<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Acl;

use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\RuntimeException;

final class UserInactiveException extends RuntimeException
{
  private readonly User $user;

  public function __construct(User $user)
  {
    parent::__construct('User inactive.');

    $this->user = $user;
  }

  public function getUser(): User
  {
    return $this->user;
  }
}
