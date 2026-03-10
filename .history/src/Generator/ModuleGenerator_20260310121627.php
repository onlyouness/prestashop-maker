<?php

namespace Youness\PrestashopMaker\Generator;

use Youness\PrestashopMaker\Utils\Tools;

class ModuleGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type == 'module';
    }

    public function generate(string $moduleName, array $data): void
    {
        $target = $this->getModulePath($moduleName) . "/{$data['name']}.php";
        $srcPath = $modulePath . DIRECTORY_SEPARATOR . 'src';
        if (!is_dir($srcPath)) {
            $this->filesystem->mkdir($srcPath);
            // Optional: add a .gitkeep if you want to commit an empty folder
            $this->filesystem->touch($srcPath . DIRECTORY_SEPARATOR . '.gitkeep');
        }
        $this->renderAndSave('module.tpl.php', $target, $data);
    }
}
