<?php

namespace Youness\PrestashopMaker\Generator;

abstract class AbstractGenerator implements GeneratorInterface
{
    protected Filesystem $filesystem;

    public function __construct(
        protected string $templateDir,
        protected string $modulesDir
    ) {
        $this->filesystem = new Filesystem();
    }
}