<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Security;

use Nette\Security\IIdentity;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\Acl\User;

final readonly class Identity implements IIdentity
{
  private UuidInterface $id;

  private string $email;

  /**
   * @var string[]
   */
  private array $roles;

  /**
   * @param string[] $roles
   */
  public function __construct(UuidInterface $id, string $email, array $roles)
  {
    $this->id = $id;
    $this->email = $email;
    $this->roles = $roles;
  }

  public static function from(User $user): IIdentity
  {
    return new self($user->getId(), $user->getEmail(), $user->getRoles());
  }

  public function getId(): UuidInterface
  {
    return $this->id;
  }

  /**
   * @return string[]
   */
  public function getRoles(): array
  {
    return $this->roles;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * @return array{'email': string}
   */
  public function getData(): array
  {
    return [
      'email' => $this->getEmail(),
    ];
  }
}
