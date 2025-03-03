<?php

declare(strict_types=1);

namespace VsPoint\Exception;

use Contributte\Events\Extra\Event\Application\ErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\ILogger;

final readonly class ErrorLoggerSubscriber implements EventSubscriberInterface
{
  private ILogger $logger;

  public function __construct(ILogger $logger)
  {
    $this->logger = $logger;
  }

  /**
   * @return array<string, string>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      ErrorEvent::class => 'onError',
    ];
  }

  public function onError(ErrorEvent $event): void
  {
    $e = $event->getThrowable();
    $code = $e->getCode();
    $level = $code >= 400 && $code <= 499 ? ILogger::WARNING : ILogger::ERROR;
    $this->logger->log($e, $level);

    // $app = $event->getApplication();
    // $presenter = $app->getPresenter();

    // if ($presenter instanceof AdminBasePresenter) {
    //    $app->errorPresenter = trim(AdminErrorPresenter::LINK, ':');
    // }
  }
}
