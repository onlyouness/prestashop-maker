<?php

namespace Youness\PrestashopMaker\Generator;

interface GeneratorInterface
{
    /**
     * returns true if the type is the correspond
     *
     * @param string $type
     * @return boolean
     */
    public function supports(string $type): bool;
    public function generate(string $moduleName, array $data): void;
}
