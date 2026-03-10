<?php

namespace Youness\PrestashopMaker\Utils;

class Tools
{
    public function __construct(private string $modulesDir) {}
    public static function asPascalCase(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name;
    }
    public static function dd(array $i)
    {
        var_dump([$i]);
        die;
    }
    public function getModules()
    {
        $this->modulesDir;
        $dir_path = $this->modulesDir;
        $folders = array();
        $items = scandir($dir_path);

        foreach ($items as $item) {
            // Exclude the current directory ('.') and parent directory ('..')
            if ($item != '.' && $item != '..') {
                $item_path = $dir_path . DIRECTORY_SEPARATOR . $item;
                // Check if the item is a directory
                if (is_dir($item_path)) {
                    $folders[] = $item; // Add the folder name to the array
                }
            }
        }

        // Print the list of folders
        return $folders);
    }
}
