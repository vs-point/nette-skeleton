<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\PHPStan\Type\Doctrine\Descriptors;

use Brick\DateTime\ZonedDateTime;
use PHPStan\Type\Doctrine\Descriptors\DoctrineTypeDescriptor;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Solcik\Doctrine\DBAL\Type\ZonedDateTimeType as ZonedDateTimeTypeDBAL;

class ZonedDateTimeType implements DoctrineTypeDescriptor
{
  public function getType(): string
  {
    return ZonedDateTimeTypeDBAL::class;
  }

  public function getWritableToPropertyType(): Type
  {
    return new ObjectType(ZonedDateTime::class);
  }

  public function getWritableToDatabaseType(): Type
  {
    return new ObjectType(ZonedDateTime::class);
  }

  public function getDatabaseInternalType(): Type
  {
    return new StringType();
  }
}
