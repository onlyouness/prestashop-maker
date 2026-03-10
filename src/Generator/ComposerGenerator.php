<?php

namespace Youness\PrestashopMaker\Generator;

class ComposerGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type === 'composer';
    }

    public function generate(string $moduleName, array $data): void
    {
        $target = $this->getModulePath($moduleName) . '/composer.json';

        // Ensure the directory exists before saving
        $this->renderAndSave('composer.json.tpl.php', $target, $data);
    }
}
