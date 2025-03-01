<?php

declare(strict_types=1);

namespace VsPoint\Entity\Acl;

use Brick\DateTime\ZonedDateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToMany;
use Ds\Sequence;
use Ds\Vector;
use Nette\Security\Passwords;
use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;
use VsPoint\Domain\Acl\User\DoesUserExist;
use VsPoint\Domain\Acl\User\UserCreated;
use VsPoint\Domain\Acl\User\UserEdited;
use VsPoint\Domain\Acl\User\UserLoggedIn;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleted;
use VsPoint\Entity\HasId;
use VsPoint\Exception\Runtime\Acl\UserAlreadyExistsException;
use VsPoint\Exception\Runtime\Authentication\InvalidPasswordException;
use VsPoint\Exception\Runtime\Authentication\UserInactiveException;

#[ORM\Entity]
#[ORM\Table(name: 'acl_user')]
class User implements HasId, Stringable
{
  #[Column(type: 'string', unique: true, nullable: false)]
  private string $email;

  #[Column(type: 'string', nullable: false)]
  private string $password;

  #[Column(type: 'brick_zoneddatetime', precision: 6, nullable: true)]
  private ?ZonedDateTime $expiration;

  #[Column(type: 'brick_zoneddatetime', precision: 6, nullable: false)]
  private ZonedDateTime $createdAt;

  #[Column(type: 'brick_zoneddatetime', precision: 6, nullable: true)]
  private ?ZonedDateTime $gdpr;

  #[ORM\Id]
  #[Column(type: 'uuid')]
  private UuidInterface $id;

  /**
   * @var Collection<int, UserRole>
   */
  #[OneToMany(mappedBy: 'user', targetEntity: UserRole::class)]
  private Collection $userRoles;

  /**
   * @throws UserAlreadyExistsException
   */
  private function __construct(
    UuidInterface $uuid,
    string $email,
    string $password,
    ?ZonedDateTime $expiration,
    ZonedDateTime $createdAt,
    ?ZonedDateTime $gdpr,
    DoesUserExist $doesUserExist,
    Passwords $service,
    UserCreated $created,
  ) {
    $email = Strings::lower($email);

    if ($doesUserExist->__invoke($email)) {
      throw new UserAlreadyExistsException();
    }

    $this->id = $uuid;
    $this->email = $email;
    $this->expiration = $expiration;
    $this->createdAt = $createdAt;
    $this->gdpr = $gdpr;
    $this->password = $service->hash($password);

    $this->userRoles = new ArrayCollection();

    $created->__invoke($this);
  }

  public function __toString(): string
  {
    return $this->getEmail();
  }

  /**
   * @throws UserAlreadyExistsException
   */
  public static function create(
    UuidInterface $uuid,
    string $email,
    string $password,
    ?ZonedDateTime $expiration,
    ZonedDateTime $createdAt,
    ?ZonedDateTime $gdpr,
    DoesUserExist $doesUserExist,
    Passwords $service,
    UserCreated $created,
  ): self {
    return new self(
      $uuid,
      $email,
      $password,
      $expiration,
      $createdAt,
      $gdpr,
      $doesUserExist,
      $service,
      $created
    );
  }

  /**
   * @throws UserAlreadyExistsException
   */
  public function edit(
    string $email,
    ?ZonedDateTime $expiration,
    DoesUserExist $doesUserExist,
    UserEdited $edited,
  ): self {
    if ($doesUserExist->__invoke($email, $this)) {
      throw new UserAlreadyExistsException();
    }

    $this->email = $email;
    $this->expiration = $expiration;

    $edited->__invoke($this);

    return $this;
  }

  public function editPassword(string $password, Passwords $service, UserEdited $edited): self
  {
    $this->password = $service->hash($password);

    $edited->__invoke($this);

    return $this;
  }

  /**
   * @param array<string> $requestedRoles
   */
  public function editUserRoles(
    array $requestedRoles,
    UserRoleCreated $userRoleCreated,
    UserRoleDeleted $userRoleDeleted,
  ): self {
    $oldUserRoles = $this->getUserRoles();
    foreach ($oldUserRoles as $oldUserRole) {
      $userRoleDeleted->__invoke($oldUserRole);
    }

    $newUserRoles = new ArrayCollection();
    foreach ($requestedRoles as $requestedRole) {
      $newUserRoles->add(
        UserRole::create(Uuid::uuid4(), $this, Role::create($requestedRole), $userRoleCreated)
      );
    }
    $this->userRoles = $newUserRoles;

    return $this;
  }

  /**
   * @throws InvalidPasswordException
   * @throws UserInactiveException
   */
  public function logIn(
    Passwords $service,
    string $password,
    ZonedDateTime $timestamp,
    UserLoggedIn $loggedIn,
  ): void {
    if (!$service->verify($password, $this->getPasswordHash())) {
      throw new InvalidPasswordException();
    }

    if (!$this->isActive($timestamp)) {
      throw new UserInactiveException($this);
    }

    $loggedIn->__invoke($this);
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function getExpiration(): ?ZonedDateTime
  {
    return $this->expiration;
  }

  public function getCreatedAt(): ZonedDateTime
  {
    return $this->createdAt;
  }

  public function getId(): UuidInterface
  {
    return $this->id;
  }

  public function getGdpr(): ?ZonedDateTime
  {
    return $this->gdpr;
  }

  public function isActive(ZonedDateTime $timestamp): bool
  {
    $expiration = $this->getExpiration();

    if ($expiration === null) {
      return true;
    }

    return $expiration->isAfter($timestamp);
  }

  /**
   * Returns all user's ACL roles names incl. "user" role.
   *
   * @return array<string>
   */
  public function getRoles(): array
  {
    return array_unique(
      $this->getUserRoles()->reduce(
        static function (array $acc, UserRole $userRole): array {
          $acc[] = $userRole->getRole()->getTitle();

          return $acc;
        },
        ['user']
      )
    );
  }

  /**
   * @return Sequence<UserRole>
   */
  public function getUserRoles(): Sequence
  {
    return new Vector($this->userRoles->getIterator());
  }

  /**
   * Returns all user's UserRoles names doesn't incl. "user" role because it is not defined in UserRole table.
   *
   * @return array<string>
   */
  public function getUserRolesList(): array
  {
    return $this->getUserRoles()->reduce(
      static function (array $items, UserRole $userRole): array {
        $items[] = $userRole->getRole()->getTitle();

        return $items;
      },
      []
    );
  }

  public function isPasswordCorrect(string $password, Passwords $passwords): bool
  {
    return $passwords->verify($password, $this->password);
  }

  private function getPasswordHash(): string
  {
    return $this->password;
  }
}
