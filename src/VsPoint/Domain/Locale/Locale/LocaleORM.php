<?php

declare(strict_types=1);

namespace VsPoint\Domain\Locale\Locale;

use Doctrine\ORM\EntityManagerInterface;
use VsPoint\Entity\Locale\Locale;

final readonly class LocaleORM implements LocaleCreated
{
  public function __construct(
    private EntityManagerInterface $em,
  ) {
  }

  public function __invoke(Locale $locale): void
  {
    $this->em->persist($locale);
  }
}
