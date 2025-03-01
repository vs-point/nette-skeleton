<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Locale\Locale;

use Throwable;
use VsPoint\Exception\Runtime\EntityNotFoundException;

final class LocaleNotFoundById extends EntityNotFoundException
{
  public function __construct(
    private readonly string $id,
    Throwable $e,
  ) {
    parent::__construct(
      sprintf('Locale was not found for id: \'%s\'', $this->id),
      0,
      $e
    );
  }

  public function getId(): string
  {
    return $this->id;
  }
}
