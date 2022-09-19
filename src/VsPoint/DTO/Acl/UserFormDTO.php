<?php

declare(strict_types=1);

namespace VsPoint\DTO\Acl;

use Brick\DateTime\LocalDate;
use Nette\SmartObject;

final class UserFormDTO
{
  use SmartObject;

  public string $email;

  public string $password;

  public ?LocalDate $expiration = null;
}
