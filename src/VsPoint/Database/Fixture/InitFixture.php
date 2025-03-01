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
  public const string USER_01 = '0d0a760f-501f-467c-9d92-cf2e47726d8e';

  public const string USER_ROLE_POWER_USER = '5b271a91-cee2-41f4-85c5-7cb67146358e';

  public const string USER_EMAIL_DAVID_SOLC_VS_POINT_CZ = 'david.solc@vs-point.cz';

  public const string USER_PASS = 'MFD_mpb3vjw8wcb.tvq';

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
      Uuid::fromString(self::USER_01),
      self::USER_EMAIL_DAVID_SOLC_VS_POINT_CZ,
      self::USER_PASS,
      null,
      $this->clock->createZonedDateTime(),
      null,
      $this->doesUserExist,
      $this->passwords,
      $this->userCreated
    );

    $powerUserRole = Role::create(Role::POWER_USER);
    UserRole::create(Uuid::fromString(self::USER_ROLE_POWER_USER), $user01, $powerUserRole, $this->userRoleCreated);

    $manager->flush();
  }
}
