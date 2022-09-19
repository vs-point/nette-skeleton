<?php

declare(strict_types=1);

namespace VsPoint\Domain\Acl\User;

use VsPoint\Entity\Acl\User;

interface FindUsers
{
  /**
   * @return User[]
   */
  public function __invoke(): array;
}
