<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;

interface GetUserByEmail
{
  /**
   * @throws UserNotFoundByEmail
   */
  public function __invoke(string $email): User;
}
