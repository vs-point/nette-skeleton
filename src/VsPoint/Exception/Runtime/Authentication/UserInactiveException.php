<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Authentication;

use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\RuntimeException;

final class UserInactiveException extends RuntimeException
{
  public function __construct(
    private User $user,
  ) {
    parent::__construct('User inactive.');
  }

  public function getUser(): User
  {
    return $this->user;
  }
}
