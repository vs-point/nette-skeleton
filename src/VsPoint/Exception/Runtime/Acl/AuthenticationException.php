<?php

declare(strict_types=1);

namespace VsPoint\Exception\Runtime\Acl;

use Nette\Security\AuthenticationException as NetteAuthenticationException;

final class AuthenticationException extends NetteAuthenticationException
{
}
