<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception;

use Contributte\Events\Extra\Event\Application\ErrorEvent;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tracy\ILogger;
use VsPoint\Exception\ErrorLoggerSubscriber;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Exception\ErrorLoggerSubscriber
 */
final class ErrorLoggerSubscriberTest extends TestCase
{
  use MockeryPHPUnitIntegration;

  /**
   * @group unit
   */
  public function testConstructor(): void
  {
    $logger = Mockery::mock(ILogger::class);

    $errorLoggerSubscriber = new ErrorLoggerSubscriber($logger);

    self::assertCount(1, $errorLoggerSubscriber::getSubscribedEvents());
  }

  /**
   * @group unit
   */
  public function testOnError(): void
  {
    $logger = Mockery::mock(ILogger::class);

    $errorLoggerSubscriber = new ErrorLoggerSubscriber($logger);

    $eCode = 500;
    $eMessage = 'exception';
    $exception = new \Exception($eMessage, $eCode);

    $event = Mockery::mock(ErrorEvent::class);
    $event->allows('getThrowable')->andReturn($exception);

    $logger->allows('log')->withArgs([$exception, ILogger::ERROR])->once();

    $errorLoggerSubscriber->onError($event);
  }
}
