<?php

declare(strict_types=1);

namespace VsPoint\Entity\Acl;

use ReflectionClass;
use VsPoint\Exception\Logic\InvalidStateException;

final class Role
{
  public const POWER_USER = 'POWER_USER';

  private string $title;

  private function __construct(string $title)
  {
    if (!in_array($title, self::getAllRoles(), true)) {
      throw new InvalidStateException();
    }
    $this->title = $title;
  }

  public static function create(string $title): self
  {
    return new self($title);
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  /**
   * @return array<string>
   */
  public static function getAllRoles(): array
  {
    $rc = new ReflectionClass(self::class);

    return array_combine($rc->getConstants(), $rc->getConstants());
  }
}
