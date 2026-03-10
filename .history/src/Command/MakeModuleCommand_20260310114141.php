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
        private readonly ModuleValidator $validator
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

        

        $this->generatorManager->process('module', $name, [
            'class_name'  => $pascaleName,
            'name'        => $name,
            'author'      => $author,
            'description' => $description,
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

        return Command::SUCCESS;
    }
}
