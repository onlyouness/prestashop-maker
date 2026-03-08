<?php

namespace Youness\PrestashopMaker\Generator;

class FileGenerator
{
    public function handle(string $path, string $name, string $content): void
    {
        $folderPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;

        $this->createFolder($folderPath);

        $filePath = $folderPath . DIRECTORY_SEPARATOR . $name . '.php';

        $this->createFile($filePath, $content);
    }

    public function createFile(string $filePath, string $content): void
    {
        file_put_contents($filePath, $content);
    }

    private function createFolder(string $folderPath): void
    {
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
    }
}
