<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Entity\Acl\User;

final class FindUsersQ implements FindUsers
{
  public function __construct(
    private readonly EntityManagerInterface $em,
  ) {
  }

  /**
   * @return User[]
   */
  public function __invoke(): array
  {
    $query = $this->em
      ->createQuery(
        <<<'DQL'
                    SELECT user
                    FROM VsPoint\Entity\Acl\User AS user
                    ORDER BY user.email ASC
                    DQL
      )
        ;

    return $query->getResult();
  }
}
