<?php

namespace Youness\PrestashopMaker\Utils;

class Validator
{
    public function __construct(private string $modulesDir) {}

    public function validateName(?string $name): string
    {
        if (empty($name)) {
            throw new \RuntimeException('Name cannot be empty.');
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw new \RuntimeException('Module name must contain only letters, numbers, and underscores.');
        }
        $name = strtolower($name);
        return $name;
    }

    public function assertModuleDoesNotExist(string $name): void
    {
        if (is_dir($this->modulesDir . DIRECTORY_SEPARATOR . $name)) {
            throw new \RuntimeException(sprintf('Module "%s" already exists in %s', $name, $this->modulesDir));
        }
    }

    public function assertModuleExists(string $name): void
    {
        if (!is_dir($this->modulesDir . DIRECTORY_SEPARATOR . $name)) {
            throw new \RuntimeException(sprintf('Module "%s" does not exist in %s', $name, $this->modulesDir));
        }
    }
}
