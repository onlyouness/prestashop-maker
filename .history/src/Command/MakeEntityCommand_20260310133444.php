<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\GeneratorManager;
use Youness\PrestashopMaker\Utils\ModuleValidator;
use Youness\PrestashopMaker\Utils\Tools;

#[AsCommand(name: 'make:ps:entity', description: '')]
class MakeEntityCommand extends Command
{
    public function __construct(
        private readonly GeneratorManager $generatorManager,
        private readonly ModuleValidator $validator
    ) {
        parent::__construct();
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->choice('Select which module', Tools::getModules());
        return Command::SUCCESS;
    }
}
