<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Group;
use VsPoint\Test\TestCase;

#[CoversNothing]
final class HelloTest extends TestCase
{
  #[Group('simple')]
  public function testHello(): void
  {
    self::assertStringContainsString('!', 'Hello World!');
  }
}
