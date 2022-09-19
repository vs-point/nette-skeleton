<?php

declare(strict_types=1);

namespace VsPoint\Infrastructure\Nette\Bridges\ApplicationLatte;

use Contributte\Events\Extra\Event\Latte\TemplateCreateEvent;
use Nette\Http\IRequest;
use Nette\Localization\Translator as NetteTranslator;
use Nette\Security\User;
use Solcik\Brick\DateTime\Clock;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\Translator;
use VsPoint\Domain\Acl\User\GetUserById;
use VsPoint\Exception\Runtime\Acl\UserNotFound;
use VsPoint\Http\Web\BaseTemplate;
use VsPoint\Infrastructure\Nette\Security\Identity;

final class BaseTemplateSubscriber implements EventSubscriberInterface
{
  public function __construct(
    private readonly NetteTranslator $translator,
    private readonly IRequest $request,
    private readonly Clock $clock,
    private readonly User $user,
    private readonly GetUserById $getUserById,
  ) {
  }

  /**
   * @return array<string, string>
   */
  public static function getSubscribedEvents(): array
  {
    return [
      TemplateCreateEvent::class => 'onCreate',
    ];
  }

  /**
   * @throws UserNotFound
   */
  public function onCreate(TemplateCreateEvent $event): void
  {
    $template = $event->getTemplate();
    if (!$template instanceof BaseTemplate) {
      return;
    }

    $translator = $this->translator;
    assert($translator instanceof Translator);

    $template->_lang = $translator->getLocale();
    $template->_timestamp = $this->clock->createZonedDateTime();
    $template->_user = $this->user;
    $template->_request = $this->request;

    if ($this->user->isLoggedIn()) {
      /** @var Identity $identity */
      $identity = $this->user->getIdentity();
      $template->aclUser = $this->getUserById->__invoke($identity->getId());
    }
  }
}
