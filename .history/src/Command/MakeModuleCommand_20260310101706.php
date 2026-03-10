<?php

namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\Generator;
use Youness\PrestashopMaker\Template\TemplateRender;
use Youness\PrestashopMaker\Utils\Tools;

#[AsCommand(name: 'make:ps:module', description: 'Generate a PrestaShop module')]
class MakeModuleCommand extends Command
{
    public $generatorManager;
    public function __construct($generatorManager)
    {
        $this->generatorManager = $generatorManager;
    }
    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io   = new SymfonyStyle($input, $output);
        $name = $io->ask('Give your module a name', null, function ($name) {
            if (empty($name)) {
                throw new \RuntimeException('Name cannot be empty.');
            }

            if (! preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
                throw new \RuntimeException('Module name must contain only letters, numbers, and underscores.');
            }
            return $name;
        });
        $author      = $io->ask('Author', 'Prestashop');
        $description = $io->ask('description', 'my module description');
        $pascaleName   = Tools::toPascalCase($name);
        $pascaleAuthor = Tools::toPascalCase($author);
        $namespace = "{$pascaleAuthor}\\{$pascaleName}";

        $this->generatorManager->process('module', $name, [
            'class_name'  => $pascaleName,
            'namespace'   => $namespace,
            'name'        => $name,
            'author'      => $author,
            'description' => $description,
        ]);
        $this->generatorManager->process('composer', $name, [
            'name_lower'        => $pascaleName,
            'author_lower'      => $pascaleAuthor,
            'namespace_escaped' => $namespace,
            'description' => $description,
        ]);

        $output->writeln('Created Succesfully');
        return Command::SUCCESS;
    }
}
