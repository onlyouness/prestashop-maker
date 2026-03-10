<?php

namespace Youness\PrestashopMaker\Generator;

interface GeneratorInterface
{
    
    public function supports(string $type): bool;
    public function generate(string $moduleName, array $data): void;
}
