<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Doctrine\ORM\EntityManagerInterface;
use loophp\collection\Collection as LoopCollection;
use loophp\collection\Contract\Collection;
use VsPoint\Entity\Locale\Locale;

final readonly class FindLocalesQ implements FindLocales
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  /**
   * @return Collection<int<0, max>, Locale>
   */
  public function __invoke(int $limit, int $offset): Collection
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

    $result = $query->getResult();

    return LoopCollection::fromIterable($result);
  }
}
