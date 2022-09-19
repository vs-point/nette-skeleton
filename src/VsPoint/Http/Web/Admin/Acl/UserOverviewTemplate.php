<?php

declare(strict_types=1);

namespace VsPoint\Http\Web\Admin\Acl;

use VsPoint\Entity\Acl\User;
use VsPoint\Http\Web\Admin\BaseTemplate;

final class UserOverviewTemplate extends BaseTemplate
{
  /**
   * @var User[]
   */
  public array $users;
}
