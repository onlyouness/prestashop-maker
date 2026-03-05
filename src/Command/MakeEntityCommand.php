<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeEntityCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:ps:entity')
            ->setDescription('Generate a PrestaShop module entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Entity generator coming soon');

        return Command::SUCCESS;
    }
}