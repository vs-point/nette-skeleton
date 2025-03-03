<?php

declare(strict_types=1);

namespace VsPoint\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'vspoint:hello', description: 'Example command to say Hello World!')]
final class HelloCommand extends Command
{
  protected function configure(): void
  {
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $io->title('Hello world...');
    $io->newLine(2);
    $io->success('...');

    return Command::SUCCESS;
  }
}
