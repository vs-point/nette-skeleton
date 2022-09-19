<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit;

use VsPoint\Test\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HelloTest extends TestCase
{
  /**
   * @group simple
   */
  public function testHello(): void
  {
    self::assertStringContainsString('!', 'Hello World!');
  }
}
