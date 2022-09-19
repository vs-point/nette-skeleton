<?php

declare(strict_types=1);

namespace VsPoint\Exception\Logic;

use Doctrine\ORM\NonUniqueResultException as DoctrineNonUniqueResultException;

final class NonUniqueResultException extends LogicException
{
  public static function from(DoctrineNonUniqueResultException $e): self
  {
    return new self($e->getMessage(), $e->getCode(), $e);
  }
}
