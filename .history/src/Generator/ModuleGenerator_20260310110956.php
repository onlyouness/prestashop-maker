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

        $this->renderAndSave('entity.tpl.php', $target, [
            'class_name' => $className,
            'namespace'  => "Youness\\Module\\{$moduleName}\\Entity",
            'table_name' => $data['table_name'] ?? strtolower($moduleName . '_' . $data['name']),
        ]);
    }
}
