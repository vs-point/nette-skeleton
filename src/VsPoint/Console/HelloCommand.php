<?php

declare(strict_types=1);

namespace VsPoint\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class HelloCommand extends Command
{
  /**
   * @var string
   */
  protected static $defaultName = 'vspoint:hello';

  public function __construct()
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->setName(self::$defaultName);
    $this->setDescription('Example command to say Hello World!');
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
