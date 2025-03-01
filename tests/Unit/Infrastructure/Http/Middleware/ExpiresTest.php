<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Infrastructure\Http\Middleware;

use DateTimeInterface;
use Brick\DateTime\TimeZone;
use Brick\DateTime\ZonedDateTime;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Psr\Http\Server\RequestHandlerInterface;
use Solcik\Brick\DateTime\Clock;
use VsPoint\Infrastructure\Http\Middleware\Expires;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Infrastructure\Http\Middleware\Expires
 */
final class ExpiresTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  public function testMiddlewareSetsExpiresHeader(): void
  {
    $timezone = TimeZone::parse('GMT');
    $currentTime = ZonedDateTime::parse('2025-02-28T12:00:00Z');
    $futureTime = $currentTime->plusMinutes(10);

    $clockMock = Mockery::mock(Clock::class);
    $middleware = new Expires($clockMock, 10);
    $clockMock
      ->shouldReceive('createZonedDateTime')
      ->andReturn($currentTime);

    $request = new ServerRequest();

    $handlerMock = Mockery::mock(RequestHandlerInterface::class);
    $handlerMock->shouldReceive('handle')
      ->with($request)
      ->andReturn(new Response());

    $response = $middleware->process($request, $handlerMock);

    $expectedExpires = $futureTime->withTimeZoneSameInstant($timezone)->toNativeDateTimeImmutable()->format(
      DateTimeInterface::RFC7231
    );
    self::assertSame($expectedExpires, $response->getHeaderLine(Expires::HEADER));
  }
}
