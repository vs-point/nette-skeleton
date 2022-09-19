<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Acl;

use Throwable;
use VsPoint\Exception\Runtime\EntityNotFoundException;

final class UserNotFoundByEmail extends EntityNotFoundException
{
  private string $email;

  public function __construct(string $email, Throwable $previous)
  {
    parent::__construct(sprintf('User was not found for email: %s', $email), 0, $previous);

    $this->email = $email;
  }

  public function getEmail(): string
  {
    return $this->email;
  }
}
