<?php

namespace Youness\PrestashopMaker\Generator;

class GeneratorManager
{
    private iterable $generators;

    // The attribute tells Symfony: "Find everything with this tag and inject it here"
    public function __construct(
        #[TaggedIterator('app.generator')] iterable $generators
    ) {
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
