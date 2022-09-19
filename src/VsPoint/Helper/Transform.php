<?php

declare(strict_types=1);

namespace VsPoint\Helper;

use Nette\Utils\Strings;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use VsPoint\Entity\HasId;

use function array_map;
use function array_values;

final class Transform
{
  /**
   * @param HasId[] $entities
   *
   * @return UuidInterface[]
   */
  public static function fromEntitiesToUuids(array $entities): array
  {
    $ids = array_map(static fn (HasId $entity): UuidInterface => $entity->getId(), $entities);

    return array_values($ids);
  }

  /**
   * @param HasId[] $entities
   *
   * @return string[]
   */
  public static function fromEntitiesToStringUuids(array $entities): array
  {
    $ids = array_map(static fn (HasId $entity): string => $entity->getId()->toString(), $entities);

    return array_values($ids);
  }

  /**
   * @param UuidInterface[] $uuids
   *
   * @return string[]
   */
  public static function fromUuidsToStringUuids(array $uuids): array
  {
    $ids = array_map(static fn (UuidInterface $uuid): string => $uuid->toString(), $uuids);

    return array_values($ids);
  }

  /**
   * @param string[] $uuids
   *
   * @return UuidInterface[]
   */
  public static function fromStringsToUuids(array $uuids): array
  {
    $ids = array_map(static fn (string $uuid): UuidInterface => Uuid::fromString($uuid), $uuids);

    return array_values($ids);
  }

  public static function getUrlWithoutSchema(string $url): string
  {
    return Strings::replace($url, '#^https?://#', '');
  }
}
