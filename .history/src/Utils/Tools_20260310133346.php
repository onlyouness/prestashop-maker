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
    public static function getModules() : array
    {
        return $self::modulesFromDisc;
    }
    public function modulesFromDisc()
    {
        $this->modulesDir;
        $dir_path = $this->modulesDir;
        $folders = array();
        $items = scandir($dir_path);

        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                $item_path = $dir_path . DIRECTORY_SEPARATOR . $item;
                if (is_dir($item_path)) {
                    $folders[] = $item;
                }
            }
        }

        return $folders;
    }
}
