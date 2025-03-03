<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http\Nette\Security;

use Nette\Security\Permission;
use PHPUnit\Framework\Attributes\CoversClass;
use VsPoint\Entity\Acl\Role;
use VsPoint\Http\Web\Admin\Acl\ChangePasswordPresenter;
use VsPoint\Http\Web\Admin\Acl\SignInPresenter;
use VsPoint\Http\Web\Admin\Acl\UserCreatePresenter;
use VsPoint\Http\Web\Admin\Acl\UserOverviewPresenter;
use VsPoint\Http\Web\Admin\HomepagePresenter;
use VsPoint\Http\Web\Admin\LandingPresenter;
use VsPoint\Http\Web\Front\HomepagePresenter as FrontHomepagePresenter;
use VsPoint\Infrastructure\Nette\Security\AuthorizatorFactory;
use VsPoint\Test\TestCase;

#[CoversClass(AuthorizatorFactory::class)]
final class AuthorizatorFactoryTest extends TestCase
{
  public function testCreateReturnsPermissionInstance(): void
  {
    $this->expectNotToPerformAssertions();

    $acl = AuthorizatorFactory::create();
  }

  public function testRolesAreAdded(): void
  {
    $acl = AuthorizatorFactory::create();

    self::assertTrue($acl->hasRole('guest'));
    self::assertTrue($acl->hasRole('authenticated'));
    self::assertTrue($acl->hasRole('user'));
  }

  public function testResourcesAreAdded(): void
  {
    $acl = AuthorizatorFactory::create();

    self::assertTrue($acl->hasResource(FrontHomepagePresenter::class));
    self::assertTrue($acl->hasResource(UserCreatePresenter::class));
    self::assertTrue($acl->hasResource(SignInPresenter::class));
    self::assertTrue($acl->hasResource(HomepagePresenter::class));
  }

  public function testRolePermissions(): void
  {
    $acl = AuthorizatorFactory::create();

    // Guest permissions
    self::assertTrue($acl->isAllowed('guest', FrontHomepagePresenter::class));
    self::assertTrue($acl->isAllowed('guest', UserCreatePresenter::class));
    self::assertTrue($acl->isAllowed('guest', SignInPresenter::class));
    self::assertFalse($acl->isAllowed('guest', HomepagePresenter::class));

    // User permissions
    self::assertFalse($acl->isAllowed('user', UserCreatePresenter::class));
    self::assertFalse($acl->isAllowed('user', SignInPresenter::class));
    self::assertTrue($acl->isAllowed('user', LandingPresenter::class));
    self::assertTrue($acl->isAllowed('user', ChangePasswordPresenter::class));

    // Power user permissions
    self::assertTrue($acl->isAllowed(Role::POWER_USER, HomepagePresenter::class));
    self::assertTrue($acl->isAllowed(Role::POWER_USER, UserOverviewPresenter::class));
    self::assertTrue($acl->isAllowed(Role::POWER_USER, Permission::All));
  }
}
