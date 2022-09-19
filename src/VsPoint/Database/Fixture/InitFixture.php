<?php

declare(strict_types=1);

namespace VsPoint\Database\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Entity\Acl\Role;
use VsPoint\Entity\Acl\User;
use VsPoint\Entity\Acl\UserRole;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;

final class InitFixture extends AbstractFixture
{
  public function __construct(
    private readonly Clock $clock,
    private readonly DoesUserExist $doesUserExist,
    private readonly UserCreated $userCreated,
    private readonly Passwords $passwords,
    private readonly UserRoleCreated $userRoleCreated,
  ) {
  }

  /**
   * @throws UserAlreadyExistsException
   */
  public function load(ObjectManager $manager): void
  {
    $user01 = User::create(
      Uuid::fromString('0d0a760f-501f-467c-9d92-cf2e47726d8e'),
      'david.solc@vs-point.cz',
      'MFD_mpb3vjw8wcb.tvq',
      null,
      $this->clock->createZonedDateTime(),
      null,
      $this->doesUserExist,
      $this->passwords,
      $this->userCreated
    );
    UserRole::create(Uuid::uuid4(), $user01, Role::create(Role::POWER_USER), $this->userRoleCreated);

    $manager->flush();
  }
}
