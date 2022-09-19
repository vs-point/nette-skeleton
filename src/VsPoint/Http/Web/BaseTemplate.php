<?php

declare(strict_types=1);

namespace VsPoint\Http\Web;

use Brick\DateTime\ZonedDateTime;
use Brick\Money\Money;
use Latte\Attributes\TemplateFilter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Http\IRequest;
use Nette\Security\User;
use Nette\SmartObject;
use stdClass;
use VsPoint\Entity\Acl\User as AclUser;

abstract class BaseTemplate extends Template
{
  use SmartObject;

  public string $baseUrl;

  public string $basePath;

  /**
   * @var stdClass[]
   */
  public array $flashes = [];

  public string $_lang;

  public IRequest $_request;

  public ZonedDateTime $_timestamp;

  public User $_user;

  public ?AclUser $aclUser;

  #[TemplateFilter]
    public function money(Money $money): string
    {
      return $money->formatTo('en_US');
    }
}
