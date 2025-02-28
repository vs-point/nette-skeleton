<?php

declare(strict_types=1);

namespace Unit\Helper;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Ramsey\Uuid\Uuid;
use VsPoint\Entity\Acl\User;
use VsPoint\Helper\Transform;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Helper\Transform
 */
final class TransformTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  public function testFromEntitiesToUuids(): void
  {
    $user01Uuid = Uuid::fromString('1f6c08d5-0ab0-489c-ad55-05651d334d96');
    $user02Uuid = Uuid::fromString('91879a07-5a39-49fe-9425-2e2444ad0845');
    $user03Uuid = Uuid::fromString('833ae818-6af0-4b18-a7e5-5a82e268652e');

    $user01 = Mockery::mock(User::class);
    $user01->allows('getId')->andReturns($user01Uuid);
    $user02 = Mockery::mock(User::class);
    $user02->allows('getId')->andReturns($user02Uuid);
    $user03 = Mockery::mock(User::class);
    $user03->allows('getId')->andReturns($user03Uuid);

    $entities = [$user01, $user02, $user03];

    $transform = Transform::fromEntitiesToUuids($entities);

    self::assertCount(3, $transform);
    self::assertSame([$user01Uuid, $user02Uuid, $user03Uuid], $transform);
  }

  public function testFromEntitiesToStringUuids(): void
  {
    $user01Uuid = Uuid::fromString('1f6c08d5-0ab0-489c-ad55-05651d334d96');
    $user02Uuid = Uuid::fromString('91879a07-5a39-49fe-9425-2e2444ad0845');
    $user03Uuid = Uuid::fromString('833ae818-6af0-4b18-a7e5-5a82e268652e');

    $user01 = Mockery::mock(User::class);
    $user01->allows('getId')->andReturns($user01Uuid);
    $user02 = Mockery::mock(User::class);
    $user02->allows('getId')->andReturns($user02Uuid);
    $user03 = Mockery::mock(User::class);
    $user03->allows('getId')->andReturns($user03Uuid);

    $entities = [$user01, $user02, $user03];

    $transform = Transform::fromEntitiesToStringUuids($entities);

    self::assertCount(3, $transform);
    self::assertSame([$user01Uuid->toString(), $user02Uuid->toString(), $user03Uuid->toString()], $transform);
  }

  public function testFromUuidsToStringUuids(): void
  {
    $user01Uuid = Uuid::fromString('1f6c08d5-0ab0-489c-ad55-05651d334d96');
    $user02Uuid = Uuid::fromString('91879a07-5a39-49fe-9425-2e2444ad0845');
    $user03Uuid = Uuid::fromString('833ae818-6af0-4b18-a7e5-5a82e268652e');

    $uuids = [$user01Uuid, $user02Uuid, $user03Uuid];

    $transform = Transform::fromUuidsToStringUuids($uuids);

    self::assertCount(3, $transform);
    self::assertSame([$user01Uuid->toString(), $user02Uuid->toString(), $user03Uuid->toString()], $transform);
  }

  public function testFromStringsToUuids(): void
  {
    $user01Uuid = '1f6c08d5-0ab0-489c-ad55-05651d334d96';
    $user02Uuid = '91879a07-5a39-49fe-9425-2e2444ad0845';
    $user03Uuid = '833ae818-6af0-4b18-a7e5-5a82e268652e';

    $uuids = [$user01Uuid, $user02Uuid, $user03Uuid];

    $transform = Transform::fromStringsToUuids($uuids);

    self::assertCount(3, $transform);
    self::assertEquals([Uuid::fromString($user01Uuid), Uuid::fromString($user02Uuid), Uuid::fromString($user03Uuid)],
      $transform
    );
  }

  public function testGetUrlWithoutSchema(): void
  {
    $url = 'https://www.example.com/';
    $transform = Transform::getUrlWithoutSchema($url);
    self::assertSame('www.example.com/', $transform);
  }
}
