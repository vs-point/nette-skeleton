<?php

declare(strict_types=1);

namespace VsPoint\Test\Unit\Command;

use VsPoint\Console\HelloCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Contributte\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use VsPoint\Test\TestCase;

/**
 * @internal
 */
#[CoversClass(HelloCommand::class)]
final class HelloCommandTest extends TestCase
{
  #[Group('command')]
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
