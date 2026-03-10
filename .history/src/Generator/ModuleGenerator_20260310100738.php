<?php

namespace Youness\PrestashopMaker\Generator;

class ModuleGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type == ''
    }

    public function generate(string $moduleName, array $data): void
    {
        
    }
}