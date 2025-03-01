<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as DoctrineNonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Nette\Utils\Strings;
use VsPoint\Entity\Acl\User;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Exception\Runtime\Acl\UserNotFoundByEmail;

final readonly class GetUserByEmailQ implements GetUserByEmail
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  /**
   * @throws UserNotFoundByEmail
   */
  public function __invoke(string $email): User
  {
    $query = $this->em
      ->createQuery(
        <<<'DQL'
                    SELECT user
                    FROM VsPoint\Entity\Acl\User AS user
                    WHERE user.email = :email
                    DQL
      )
      ->setParameter('email', Strings::lower($email))
        ;

    try {
      return $query->getSingleResult();
    } catch (DoctrineNonUniqueResultException $e) {
      throw NonUniqueResultException::from($e);
    } catch (NoResultException $e) {
      throw new UserNotFoundByEmail($email, $e);
    }
  }
}
