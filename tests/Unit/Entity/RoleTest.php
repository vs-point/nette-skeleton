<?php

declare(strict_types=1);

namespace Unit\Entity;

use VsPoint\Entity\Acl\Role;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Entity\Acl\Role
 */
final class RoleTest extends TestCase
{
  /**
   * @group unit
   */
  public function testInvoke(): void
  {
    $roleTitle = Role::POWER_USER;
    $role = Role::create($roleTitle);

    self::assertSame($roleTitle, $role->getTitle());
  }
}
