<?php

namespace Youness\PrestashopMaker\Generator;

class GeneratorManager
{
    /** @var GeneratorInterface[] */
    private iterable $generators;

    public function __construct(iterable $generators)
    {
        $this->generators = $generators;
    }

    public function process(string $type, string $moduleName, array $data): void
    {
        foreach ($this->generators as $generator) {
            if ($generator->supports($type)) {
                $generator->generate($moduleName, $data);
                return;
            }
        }

        throw new \InvalidArgumentException("No generator found for type: $type");
    }
}