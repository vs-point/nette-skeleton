<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Runtime\Acl\UserNotFound;

final readonly class GetUserByIdQ implements GetUserById
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  /**
   * @throws UserNotFound
   */
  public function __invoke(UuidInterface $id): User
  {
    /** @var null|User $user */
    $user = $this->em->find(User::class, $id);

    if ($user === null) {
      throw new UserNotFound($id);
    }

    return $user;
  }
}
