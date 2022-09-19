<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Exception\Runtime\Acl\UserNotLoggedInException;

interface GetLoggedInUser
{
  /**
   * @throws UserNotLoggedInException
   * @throws UserNotFound
   */
  public function __invoke(): User;
}
