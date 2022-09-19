<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Security\Permission;
use VsPoint\Entity\Acl\Role;
use VsPoint\Http\Web\Admin\Acl\ChangePasswordPresenter;
use VsPoint\Http\Web\Admin\Acl\SignInPresenter;
use VsPoint\Http\Web\Admin\Acl\UserCreatePresenter;
use VsPoint\Http\Web\Admin\Acl\UserEditPasswordPresenter;
use VsPoint\Http\Web\Admin\Acl\UserEditPresenter;
use VsPoint\Http\Web\Admin\Acl\UserOverviewPresenter;
use VsPoint\Http\Web\Admin\Acl\UserRolesEditPresenter;
use VsPoint\Http\Web\Admin\HomepagePresenter;
use VsPoint\Http\Web\Admin\LandingPresenter;
use VsPoint\Http\Web\Front\HomepagePresenter as FrontHomepagePresenter;

final class AuthorizatorFactory
{
  private const ROLE_SYSTEM_GUEST = 'guest';

  private const ROLE_SYSTEM_AUTHENTICATED = 'authenticated';

  private const ROLE_SYSTEM_USER = 'user';

  /**
   * @throws InvalidStateException
   * @throws InvalidArgumentException
   */
  public static function create(): Permission
  {
    $acl = new Permission();

    self::addRoles($acl);
    self::addResources($acl);
    self::setRolesPermissions($acl);

    return $acl;
  }

  private static function addRoles(Permission $acl): Permission
  {
    $acl->addRole(self::ROLE_SYSTEM_GUEST);
    $acl->addRole(self::ROLE_SYSTEM_AUTHENTICATED);
    $acl->addRole(self::ROLE_SYSTEM_USER, self::ROLE_SYSTEM_GUEST);

    $roles = Role::getAllRoles();
    foreach ($roles as $role) {
      $acl->addRole($role, self::ROLE_SYSTEM_USER);
    }

    return $acl;
  }

  /**
   * @throws InvalidStateException
   * @throws InvalidArgumentException
   */
  private static function addResources(Permission $acl): Permission
  {
    // ================================================================================================================

    $acl->addResource(FrontHomepagePresenter::class);

    $acl->addResource(UserCreatePresenter::class);
    $acl->addResource(SignInPresenter::class);
    $acl->addResource(LandingPresenter::class);
    $acl->addResource(ChangePasswordPresenter::class);
    $acl->addResource(UserOverviewPresenter::class);
    $acl->addResource(UserEditPresenter::class);
    $acl->addResource(UserEditPasswordPresenter::class);
    $acl->addResource(UserRolesEditPresenter::class);

    $acl->addResource(HomepagePresenter::class);

    return $acl;
  }

  private static function setRolesPermissions(Permission $acl): Permission
  {
    // ****************************************************************************************************************
    // GUEST (no login)

    $acl->deny(self::ROLE_SYSTEM_GUEST, null, ['out']);

    $acl->allow(self::ROLE_SYSTEM_GUEST, FrontHomepagePresenter::class);

    $acl->allow(self::ROLE_SYSTEM_GUEST, UserCreatePresenter::class);
    $acl->allow(self::ROLE_SYSTEM_GUEST, SignInPresenter::class);

    // ****************************************************************************************************************
    // USER (base login)

    $acl->deny(self::ROLE_SYSTEM_USER, UserCreatePresenter::class);
    $acl->deny(self::ROLE_SYSTEM_USER, SignInPresenter::class);

    $acl->allow(self::ROLE_SYSTEM_USER, null, ['out']);
    $acl->allow(self::ROLE_SYSTEM_USER, LandingPresenter::class);
    $acl->allow(self::ROLE_SYSTEM_USER, ChangePasswordPresenter::class);

    // ****************************************************************************************************************
    // POWER USER - TODO - change to some kind of admin

    $acl->allow(Role::POWER_USER, HomepagePresenter::class);
    $acl->allow(Role::POWER_USER, UserOverviewPresenter::class);
    $acl->allow(Role::POWER_USER, UserEditPresenter::class);
    $acl->allow(Role::POWER_USER, UserEditPasswordPresenter::class);
    $acl->allow(Role::POWER_USER, UserRolesEditPresenter::class);

    // ****************************************************************************************************************
    // POWER USER

    $acl->allow(Role::POWER_USER, Permission::ALL);

    return $acl;
  }
}
