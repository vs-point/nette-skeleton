<?php

declare(strict_types=1);

namespace VsPoint\Exception\Logic;

use Throwable;

final class InvalidStateException extends LogicException
{
  public static function fromPrevious(Throwable $previous): self
  {
    return new self($previous->getMessage(), $previous->getCode(), $previous);
  }
}
