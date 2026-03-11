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

#[AsCommand(name: 'make:ps:module', description: 'Generate a PrestaShop module')]
class MakeModuleCommand extends Command
{
    public function __construct(
        private readonly GeneratorManager $generatorManager,
        private readonly Valida $validator
    ) {
        parent::__construct();
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $name = $io->ask('Give your module a name', null, function ($name) {
            $name = $this->validator->validateName($name);
            $this->validator->assertModuleDoesNotExist($name);
            return $name;
        });
        $author      = $io->ask('Author', 'Prestashop');
        $description = $io->ask('description', 'my module description');

        $hooksInput = $io->ask('Enter hooks separated by comma (e.g. displayHeader,actionAdminControllerSetMedia)', 'displayHeader');

        $hooks = array_map('trim', explode(',', $hooksInput));


        $pascaleName   = Tools::asPascalCase($name);
        $pascaleAuthor = Tools::asPascalCase($author);
        $namespace = "{$pascaleAuthor}\\{$pascaleName}";
        $composerName = strtolower($author) . '/' . strtolower($name);

        $registrations = "";
        $methods = "";

        if (!empty($hooks)) {
            $registrations = "&& \$this->registerHook([\n";
            foreach ($hooks as $hook) {
                $registrations .= "                '" . $hook . "',\n";
            }
            $registrations .= "            ])";

            foreach ($hooks as $hook) {
                $methods .= "\n    public function hook" . ucfirst($hook) . "(\$params)\n";
                $methods .= "    {\n";
                $methods .= "        // Logic for " . $hook . "\n";
                $methods .= "    }\n";
            }
        }

        $this->generatorManager->process('module', $name, [
            'class_name'  => $pascaleName,
            'name'        => $name,
            'author'      => $author,
            'description' => $description,
            'hooks' => $hooks,
            'registrations' => $registrations,
            'methods' => $methods
        ]);
        $this->generatorManager->process('composer', $name, [
            'composerName' => $composerName,
            'namespace_escaped'    => str_replace('\\', '\\\\', $namespace),
            'description' => $description,
        ]);

        $io->newLine();
        $io->success([
            "Module $name created successfully!",
            "Location: modules/$name",
        ]);

        $io->info('Next steps:');
        $io->writeln([
            " 1. <fg=yellow>cd modules/$name</>",
            " 2. <fg=yellow>composer install</>",
            " 3. Install the module in PrestaShop Back Office. or simply run: \n <fg=yellow>php bin/console prestashop:module install $name</> "
        ]);

        return Command::SUCCESS;
    }
}
