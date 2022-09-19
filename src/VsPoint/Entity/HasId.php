<?php

declare(strict_types=1);

namespace VsPoint\Entity;

use Ramsey\Uuid\UuidInterface;

interface HasId
{
  public function getId(): UuidInterface;
}
