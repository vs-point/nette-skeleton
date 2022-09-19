<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin;

use VsPoint\Entity\Acl\User;
use VsPoint\Http\Web\BaseTemplate as BBaseTemplate;

abstract class BaseTemplate extends BBaseTemplate
{
  public ?User $aclUser;
}
