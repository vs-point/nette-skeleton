<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Authentication;

use VsPoint\Exception\Runtime\RuntimeException;

final class InvalidPasswordException extends RuntimeException
{
  public function __construct()
  {
    parent::__construct('Invalid password.');
  }
}
