<?php

declare(strict_types=1);

namespace VsPoint\DTO\Acl;

use Nette\SmartObject;

final class SignInFormDTO
{
  use SmartObject;

  public string $email;

  public string $password;
}
