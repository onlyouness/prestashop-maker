<?php
namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\Generator;
use Youness\PrestashopMaker\Template\TemplateRender;

#[AsCommand(name: 'make:ps:module', description: 'Generate a PrestaShop module')]
class MakeModuleCommand extends Command
{
    public $generatorManager;
    public function __construct($generatorManager)
    {
        $this->generatorManager = $generatorManager;
    }
    protected function configure(): void
    {}

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
        // $this->dd([$name]);
        $pascaleName   = $this->toPascalCase($name);
        $pascaleAuthor = $this->toPascalCase($author);
        $namespace = "{$pascaleAuthor}\\{$pascaleName}";

        $this->generatorManager->process('module', $name, ['author' => $]);
        // $this->dd([$pascaleAuthor,$pascaleName]);
        $generator = new Generator();
        $generator->generate('composer.json', [
            'name_lower'        => $pascaleName,
            'author_lower'      => $pascaleAuthor,
            'namespace_escaped' => $namespace,
            'description' => $description,
        ]);
        $generator->generate('module', [
                'class_name'  => $pascaleName,
                'namespace'   => $namespace,
                'name'        => $name,
                'author'      => $author,
                'description' => $description,
            ]);
        // $composer = (new TemplateRender())->render(
        //     'composer.json.tpl.php',
        //     [
        //         'name_lower'        => $pascaleName,
        //         'author_lower'      => $pascaleAuthor,
        //         'namespace_escaped' => "{$pascaleAuthor}\\{$pascaleName}",
        //         'description' => $description,
        //     ]);

        // $content = (new TemplateRender())->render(
        //     'module.tpl.php',
        //     [
        //         'class_name'  => 'te',
        //         'namespace'   => 'namespace',
        //         'name'        => $name,
        //         'author'      => $author,
        //         'description' => $description,
        //     ]);
        // $this->dd([$content, $composer]);
        $output->writeln('Created Succesfully');
        return Command::SUCCESS;
    }
    public function dd(array $i)
    {
        var_dump([$i]);
        die;
    }

    public function toPascalCase(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name;
    }
}
