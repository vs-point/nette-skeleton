<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use Nette\Security\User as SecurityUser;
use Ramsey\Uuid\Uuid;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Exception\Runtime\Acl\UserNotLoggedInException;

final class GetLoggedInUserQ implements GetLoggedInUser
{
  public function __construct(
    private GetUserById $getUserById,
    private SecurityUser $security,
  ) {
  }

  /**
   * @throws UserNotLoggedInException
   * @throws UserNotFound
   */
  public function __invoke(): User
  {
    if (!$this->security->isLoggedIn()) {
      throw new UserNotLoggedInException();
    }

    $id = $this->security->getId();

    return $this->getUserById->__invoke(Uuid::fromString($id));
  }
}
