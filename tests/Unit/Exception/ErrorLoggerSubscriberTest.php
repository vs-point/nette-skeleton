<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Exception;
use Contributte\Events\Extra\Event\Application\ErrorEvent;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tracy\ILogger;
use VsPoint\Exception\ErrorLoggerSubscriber;
use VsPoint\Test\TestCase;

#[CoversClass(ErrorLoggerSubscriber::class)]
final class ErrorLoggerSubscriberTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  #[Group('unit')]
  public function testConstructor(): void
  {
    $logger = Mockery::mock(ILogger::class);

    $errorLoggerSubscriber = new ErrorLoggerSubscriber($logger);

    self::assertCount(1, $errorLoggerSubscriber::getSubscribedEvents());
  }

  #[Group('unit')]
  public function testOnError(): void
  {
    $logger = Mockery::mock(ILogger::class);

    $errorLoggerSubscriber = new ErrorLoggerSubscriber($logger);

    $eCode = 500;
    $eMessage = 'exception';
    $exception = new Exception($eMessage, $eCode);

    $event = Mockery::mock(ErrorEvent::class);
    $event->allows('getThrowable')->andReturn($exception);

    $logger->allows('log')->withArgs([$exception, ILogger::ERROR])->once();

    $errorLoggerSubscriber->onError($event);
  }
}
