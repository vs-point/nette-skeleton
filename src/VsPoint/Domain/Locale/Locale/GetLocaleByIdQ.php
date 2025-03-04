<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException as ORMNonUniqueResultException;
use Doctrine\ORM\NoResultException;
use VsPoint\Entity\Locale\Locale;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Exception\Runtime\Locale\Locale\LocaleNotFoundById;

final readonly class GetLocaleByIdQ implements GetLocaleById
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  /**
   * @throws NonUniqueResultException
   * @throws LocaleNotFoundById
   */
  public function __invoke(string $id): Locale
  {
    $query = $this->em
      ->createQuery(
        <<<'DQL'
                    SELECT locale
                    FROM VsPoint\Entity\Locale\Locale AS locale
                    WHERE locale.id = :id
                    DQL
      )
      ->setParameter('id', $id)
    ;

    try {
      return $query->getSingleResult(AbstractQuery::HYDRATE_OBJECT);
    } catch (ORMNonUniqueResultException $e) {
      throw NonUniqueResultException::from($e);
    } catch (NoResultException $e) {
      throw new LocaleNotFoundById($id, $e);
    }
  }
}
