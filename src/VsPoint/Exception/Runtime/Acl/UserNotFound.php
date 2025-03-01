<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Acl;

use Ramsey\Uuid\UuidInterface;
use VsPoint\Exception\Runtime\EntityNotFoundException;

final class UserNotFound extends EntityNotFoundException
{
  private readonly UuidInterface $id;

  public function __construct(UuidInterface $id)
  {
    parent::__construct('User was not found for id: ' . $id->toString());

    $this->id = $id;
  }

  public function getId(): UuidInterface
  {
    return $this->id;
  }
}
