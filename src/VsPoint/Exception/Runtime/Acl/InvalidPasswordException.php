<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Acl;

use VsPoint\Exception\Runtime\RuntimeException;

final class InvalidPasswordException extends RuntimeException
{
  public function __construct()
  {
    parent::__construct('Invalid password.');
  }
}
