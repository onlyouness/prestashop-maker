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
        private readonly ModuleValidator $validator,
        private string $modulesDir,
    ) {
        parent::__construct();
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $module = $io->choice('Select which module', Tools::getModules($this->modulesDir));

        $fields = [];
        $io->section('Add fields to your entity (press enter to stop)');

        while (true) {
            $fieldName = $io->ask('Field name (e.g. title)');
            if (!$fieldName) break;

            $type = $io->choice('Field type', ['string', 'integer', 'text', 'boolean', 'datetime'], 'string');
            $translatable = $io->confirm('Is this field translatable', false);
            $fields[$fieldName] = $type;
            $fields[$fieldName] = $translatable;
        }
        return Command::SUCCESS;
    }
}
