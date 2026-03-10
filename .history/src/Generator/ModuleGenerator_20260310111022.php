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
        $className = Tools::asPascalCase($data['name']);
        $target = $this->getModulePath($moduleName) . "/{$className}.php";

        $this->renderAndSave('module.tpl.php', $target, $data);
    }
}
