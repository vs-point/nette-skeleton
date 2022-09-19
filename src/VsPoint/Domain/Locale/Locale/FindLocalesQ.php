<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Doctrine\ORM\EntityManagerInterface;
use Ds\Sequence;
use Ds\Vector;
use VsPoint\Entity\Locale\Locale;

final class FindLocalesQ implements FindLocales
{
  public function __construct(private readonly EntityManagerInterface $em)
  {
  }

  /**
   * @return Sequence<Locale>
   */
  public function __invoke(int $limit, int $offset): Sequence
  {
    $query = $this->em
      ->createQuery(
        <<<'DQL'
                    SELECT locale
                    FROM VsPoint\Entity\Locale\Locale as locale
                    DQL
      )
      ->setFirstResult($offset)
      ->setMaxResults($limit)
        ;

    return new Vector($query->getResult());
  }
}
