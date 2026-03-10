<?php
namespace Youness\PrestashopMaker\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Youness\PrestashopMaker\Generator\Generator;

#[AsCommand(name: 'make:ps:module', description: 'Generate a PrestaShop module')]
class MakeModuleCommand extends Command
{
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
        $namespace     = "{$pascaleAuthor}\\\{$pascaleName}";
        $this->dd([$namespace,$pascaleAuthor,$pascaleName]);
       
        $content = $this->render('module.tpl.php', [
            'class_name'  => $pascaleName,
            'namespace'   => $namespace,
            'name'        => $name,
            'author'      => $author,
            'description' => $description,
        ]);
        $this->handle(__DIR__ . '/../../modules', $name, $content);

         $content   = $this->render('composer.json.tpl.php', [
            'name_lower'        => $pascaleName,
            'author_lower'      => $pascaleAuthor,
            'namespace_escaped' => $namespace,
            'description'       => $description,
        ]);
        $this->handleComposer(__DIR__ . '/../../modules/'.$name, 'composer', $content);

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
    public function render(string $template, array $variables): string
    {
        $content = file_get_contents(__DIR__ . "/../../templates/$template");

        foreach ($variables as $key => $value) {
            $content = str_replace("{{" . $key . "}}", $value, $content);
        }

        return $content;
    }
    public function handle(string $path, string $name, string $content): void
    {
        $folderPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;

        $this->createFolder($folderPath);

        $filePath = $folderPath . DIRECTORY_SEPARATOR . $name . '.php';

        $this->createFile($filePath, $content);
    }
    public function handleComposer(string $path, string $name, string $content): void
    {

        $filePath = $path . DIRECTORY_SEPARATOR . $name . '.json';

        $this->createFile($filePath, $content);
    }

    public function createFile(string $filePath, string $content): void
    {
        file_put_contents($filePath, $content);
    }

    private function createFolder(string $folderPath): void
    {
        if (! is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
    }
}
