<?php

namespace Youness\PrestashopMaker\Generator;

class RepositoryGenerator extends AbstractGenerator 
{
    public function supports(string $type): bool
    {
        return $type == 'repository';
    }
    public function generate(string $moduleName, array $data): void
    {
        $target = $this->getModulePath($moduleName) . "/src/Repository/{$data['entity_name']}Repository.php";
       
        $this->renderAndSave('repository.tpl.php', $target, $data);
    }
}
