<?php

declare(strict_types=1);

namespace VsPoint\DTO\Acl;

use Nette\SmartObject;

class PasswordFormDTO
{
  use SmartObject;

  public string $password;
}
