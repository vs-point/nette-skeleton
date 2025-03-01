<?php

declare(strict_types=1);

namespace VsPoint\Entity\Acl;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Domain\Acl\UserRole\UserRoleCreated;
use VsPoint\Domain\Acl\UserRole\UserRoleDeleted;
use VsPoint\Entity\HasId;

#[ORM\Entity]
#[ORM\Table(name: 'acl_userrole', options: [
  'collate' => 'cs_CZ',
])]
class UserRole implements HasId
{
  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private UuidInterface $id;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userRoles')]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
  private User $user;

  #[ORM\Column(type: 'string', nullable: false)]
  private string $role;

  private function __construct(UuidInterface $id, User $user, Role $role, UserRoleCreated $userRoleCreated)
  {
    $this->id = $id;
    $this->user = $user;
    $this->role = $role->getTitle();

    $userRoleCreated->__invoke($this);
  }

  public static function create(
    UuidInterface $id,
    User $user,
    Role $role,
    UserRoleCreated $userRoleCreated,
  ): self {
    return new self($id, $user, $role, $userRoleCreated);
  }

  public function delete(UserRoleDeleted $userRoleDeleted): self
  {
    $userRoleDeleted->__invoke($this);

    return $this;
  }

  public function getId(): UuidInterface
  {
    return $this->id;
  }

  public function getUser(): User
  {
    return $this->user;
  }

  public function getRole(): Role
  {
    return Role::create($this->role);
  }
}
