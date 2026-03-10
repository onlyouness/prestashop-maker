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

    /**
     * responsible for generating the 
     *
     * @param string $moduleName
     * @param array $data
     * @return void
     */
    public function generate(string $moduleName, array $data): void;
}
