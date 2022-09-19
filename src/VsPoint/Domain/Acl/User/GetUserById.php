<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;

interface GetUserById
{
  /**
   * @throws UserNotFound
   */
  public function __invoke(UuidInterface $id): User;
}
