<?php

declare(strict_types=1);

namespace VsPoint\Entity\Locale;

use Doctrine\ORM\Mapping as ORM;
use Stringable;
use VsPoint\Domain\Locale\Locale\LocaleCreated;

#[ORM\Entity]
#[ORM\Table(name: 'locale_locale')]
class Locale implements Stringable
{
  public const ENG = 'eng';

  public const CZE = 'cze';

  #[ORM\Id]
    #[ORM\Column(type: 'string', length: 3)]
    private string $id;

  public function __construct(string $id, LocaleCreated $created)
  {
    $this->id = $id;

    $created->__invoke($this);
  }

  public function __toString(): string
  {
    return $this->getId();
  }

  public static function of(string $id): self
  {
    $created = new class() implements LocaleCreated {
      public function __invoke(Locale $locale): void
      {
      }
    };

    return new self($id, $created);
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function isEqual(self $other): bool
  {
    return $this->getId() === $other->getId();
  }
}
