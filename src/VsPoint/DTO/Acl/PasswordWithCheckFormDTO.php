<?php

declare(strict_types=1);

namespace VsPoint\DTO\Acl;

use Nette\SmartObject;

final class PasswordWithCheckFormDTO extends PasswordFormDTO
{
  use SmartObject;

  public string $currentPassword;
}
