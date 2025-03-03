<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Entity\Acl\Role;
use VsPoint\Exception\Logic\InvalidStateException;
use VsPoint\Test\TestCase;

#[CoversClass(Role::class)]
final class RoleTest extends TestCase
{
  #[Group('unit')]
  public function testInvoke(): void
  {
    $roleTitle = Role::POWER_USER;
    $role = Role::create($roleTitle);

    self::assertSame($roleTitle, $role->getTitle());
  }

  #[Group('unit')]
  public function testInvalidRoleThrowsException(): void
  {
    $this->expectException(InvalidStateException::class);

    Role::create('NON_EXISTENT_ROLE');
  }
}
