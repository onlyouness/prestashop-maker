<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Youness\PrestashopMaker\Generator\GeneratorManager;
use Youness\PrestashopMaker\Utils\ModuleValidator;

#[AsCommand(name: 'ps:make:entity', description: '')]
class MakeEntityCommand extends Command {
    public function __construct(
        private readonly GeneratorManager $generatorManager,
        private readonly ModuleValidator $validator
    ) {
        parent::__construct();
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command
    }
}
