<?php

declare(strict_types=1);

namespace VsPoint\DTO\Acl;

use Nette\SmartObject;

final class UserRolesFormDTO
{
  use SmartObject;

  /**
   * @var array<int, string>
   */
  public array $roles;
}
