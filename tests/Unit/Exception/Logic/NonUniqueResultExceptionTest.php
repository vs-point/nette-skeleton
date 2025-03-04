<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Exception\Logic;

use Doctrine\ORM\NonUniqueResultException as DoctrineNonUniqueResultException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Exception\Logic\NonUniqueResultException;
use VsPoint\Test\TestCase;

#[CoversClass(NonUniqueResultException::class)]
final class NonUniqueResultExceptionTest extends TestCase
{
  #[Group('unit')]
  public function testConstructor(): void
  {
    $message = 'exception';

    $prevException = new DoctrineNonUniqueResultException($message);

    $exception = NonUniqueResultException::from($prevException);

    self::assertSame($message, $exception->getMessage());
  }
}
