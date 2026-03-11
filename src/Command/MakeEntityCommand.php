<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\GeneratorManager;
use Youness\PrestashopMaker\Utils\Tools;
use Youness\PrestashopMaker\Utils\Validator;

#[AsCommand(name: 'make:ps:entity', description: '')]
class MakeEntityCommand extends Command
{
    public function __construct(
        private readonly GeneratorManager $generatorManager,
        private readonly Validator $validator,
        private string $modulesDir,
    ) {
        parent::__construct();
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $module     = $io->choice('Select which module', Tools::getModules($this->modulesDir));
        $namespace = Tools::getModuleNamespace($this->modulesDir . '/' . $module);
        $entityName = $io->ask('Give Your Entity a name (e.g. Cat)', null, function ($entityName) {
            $this->validator->validateName($entityName);

            return Tools::asPascalCase($entityName);
        });

        $lowerEntityName = strtolower($entityName);


        $fields = [];
        $io->section('Add fields to your entity');

        while (true) {
            $fieldName = $io->ask("Add a field name (e.g. title), <fg=yellow>Press enter to stop</>");
            if (!$fieldName) break;

            $type                               = $io->choice('Field type', ['string', 'integer', 'text', 'boolean', 'datetime'], 'string');
            $nullable                       = $io->confirm('Is it nullable', true);
            $translatable                       = $io->confirm('Is this field translatable', false);
            $fields[$fieldName]['type']         = $type;
            $fields[$fieldName]['translatable'] = $translatable;
            $fields[$fieldName]['nullable'] = $nullable;
        }
        $this->generatorManager->process('sql', $module, [
            'entity_name' => $entityName,
            'table_name' => $lowerEntityName,
            'fields'      => $fields,
        ]);
        $this->generatorManager->process('entity', $module, [
            'fields'      => $fields,
            'namespace'   => $namespace,
            'entity_name' => $entityName,
            'entity_name_lower' => $lowerEntityName,
            'table_name' => $lowerEntityName,
        ]);
        $this->generatorManager->process('repository', $module, [
            'namespace'   => $namespace,
            'entity_name' => $entityName,
        ]);
        $this->generatorManager->process('service', $module, [
            'service_id' => "{$module}.repository.{$lowerEntityName}_repository",
            'definition' => [
                'class' => "{$namespace}\\Repository\\{$entityName}Repository",
                'factory' => ['@doctrine.orm.default_entity_manager', 'getRepository'],
                'arguments' => ["{$namespace}\\Entity\\{$entityName}"]
            ]
        ]);

       

        $io->success([
            "Full Entity stack for module $module generated successfully!",
        ]);

        $filesCreated = [
            "Main Entity:  modules/$module/src/Entity/$entityName.php",
            "Repository:   modules/$module/src/Repository/{$entityName}Repository.php",
        ];

        // Check the fields array to see if we generated a Lang file
        $hasTranslatable = false;
        foreach ($fields as $field) {
            if ($field['translatable'] ?? false) {
                $hasTranslatable = true;
                break;
            }
        }

        if ($hasTranslatable) {
            $filesCreated[] = "Lang Entity:  modules/$module/src/Entity/{$entityName}Lang.php";
        }

        $io->section('Files Created/Updated:');
        $io->listing($filesCreated);

        $io->writeln("<info>[OK]</info> Updated <comment>config/services.yml</comment> with the new Repository service.");

        $io->newLine();
        $io->section('Next Steps:');
        $io->writeln([
            " 1. Register the module in PrestaShop if not already done.",
            " 2. Run <fg=yellow>php bin/console cache:clear</> to register the new services.",
            " 3. Use <fg=cyan>{$entityName}Repository</> in your controllers."
        ]);

        return Command::SUCCESS;
    }
}
