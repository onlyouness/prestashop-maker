<?php

namespace Youness\PrestashopMaker\Generator;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractGenerator implements GeneratorInterface
{
    protected Filesystem $filesystem;

    public function __construct(
        protected string $templateDir,
        protected string $modulesDir
    ) {
        $this->filesystem = new Filesystem();
    }

    protected function renderAndSave(string $template, string $targetPath, array $vars): void
    {
        $templatePath = $this->templateDir . DIRECTORY_SEPARATOR . $template;

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: " . $templatePath);
        }

        extract($vars);

        // Start capturing the output
        ob_start();
        include $templatePath;
        $content = ob_get_clean();

        $this->filesystem->mkdir(dirname($targetPath));
        $this->filesystem->dumpFile($targetPath, $content);
    }

    protected function getModulePath(string $moduleName): string
    {
        return $this->modulesDir . DIRECTORY_SEPARATOR . $moduleName;
    }
}
