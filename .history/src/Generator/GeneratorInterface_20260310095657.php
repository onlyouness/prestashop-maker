<?php

namespace Youness\PrestashopMaker\Generator;

interface GeneratorInterface
{
    /**
     * Undocumented function
     *
     * @param string $type
     * @return boolean
     */
    public function supports(string $type): bool;
    public function generate(string $moduleName, array $data): void;
}
