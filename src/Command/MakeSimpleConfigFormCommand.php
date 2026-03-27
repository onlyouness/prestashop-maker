<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\GeneratorManager;
use Youness\PrestashopMaker\Utils\Validator;
use Youness\PrestashopMaker\Utils\Tools;

#[AsCommand(name: 'make:ps:simpleconfigform', description: 'Generate a PrestaShop Configuration Form')]
class MakeSimpleConfigFormCommand extends Command
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

        $formName = $io->ask('Give Your Form a name (e.g. Cat)', null, function ($formName) {
            $this->validator->validateName($formName);

            return Tools::asPascalCase($formName);
        });
        $fields = [];
        $io->section('Add fields to your form');

        while (true) {
            $fieldName = $io->ask("Add a field name (e.g. title), <fg=yellow>Press enter to stop</>");
            if (!$fieldName) break;

            $type                               = $io->choice('Field type', ['string', 'integer', 'text', 'choice', 'datetime'], 'string');
            $required                       = $io->confirm('Is it required', true);
            $default = $io->ask('Give this a default value','');
            $translatable                       = $io->confirm('Is this field translatable', false);
            $fields[$fieldName]['type']         = $type;
            $fields[$fieldName]['required'] = $required;
            $fields[$fieldName]['translatable'] = $translatable;
            $fields[$fieldName]['default'] = $default;
        }
        $this->generatorManager->process('form', $module, [
            'namespace'   => $namespace,
            'form_name' => $formName,
            'fields' => $fields,
        ]);

        $io->newLine();
        $io->success([
            "Configuration form for module $module created successfully!",
        ]);

        $filesCreated = [
            "FormType:          modules/$module/src/Form/{$formName}FormType.php",
            "DataConfiguration: modules/$module/src/Form/{$formName}DataConfiguration.php",
            "FormDataProvider:  modules/$module/src/Form/{$formName}FormDataProvider.php",
            "Controller:        modules/$module/src/Controller/{$formName}ConfigurationController.php",
            "Twig Template:     modules/$module/views/templates/admin/form.html.twig",
            "Routes:            modules/$module/config/routes.yml",
            "Services:          modules/$module/config/services.yml",
        ];

        $io->section('Files Created/Updated:');
        $io->listing($filesCreated);

        $io->newLine();
        $io->section('Next Steps:');
        $io->writeln([
            " 1. Add <fg=cyan>getContent()</> method to your module's main file to redirect to the configuration route.",
            " 2. Run <fg=yellow>php bin/console cache:clear</> to register the new services.",
            " 3. Access your module's configuration page from the BackOffice.",
        ]);

        return Command::SUCCESS;
    }
}
