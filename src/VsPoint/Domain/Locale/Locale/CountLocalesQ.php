<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

use function assert;

final readonly class CountLocalesQ implements CountLocales
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(): int
  {
    $query = $this->em
      ->createQuery(
        <<<'DQL'
                    SELECT COUNT(locale) as cnt
                    FROM VsPoint\Entity\Locale\Locale as locale
                    DQL
      )
    ;

    try {
      $result = $query->getSingleScalarResult();

      assert(is_int($result));

      return $result;
    } catch (NoResultException|NonUniqueResultException) {
      return 0;
    }
  }
}
