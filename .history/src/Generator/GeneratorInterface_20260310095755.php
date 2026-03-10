<?php

namespace Youness\PrestashopMaker\Generator;

interface GeneratorInterface
{
    /**
     * returns true if the generator supports the type ex (module)...
     *
     * @param string $type
     * @return boolean
     */
    public function supports(string $type): bool;
    public function generate(string $moduleName, array $data): void;
}
