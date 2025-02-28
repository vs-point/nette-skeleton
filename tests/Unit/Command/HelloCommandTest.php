<?php

declare(strict_types=1);

namespace Unit\Command;

use Contributte\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use VsPoint\Test\TestCase;

/**
 * @covers \VsPoint\Console\HelloCommand
 *
 * @internal
 */
final class HelloCommandTest extends TestCase
{
  /**
   * @group command
   */
  public function testExecute(): void
  {
    $container = $this->createContainer();

    $application = $container->getByType(Application::class);
    $command = $application->get('vspoint:hello');

    $commandTester = new CommandTester($command);

    $commandTester->execute([]);
    $commandTester->assertCommandIsSuccessful();
  }
}
