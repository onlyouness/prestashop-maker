<?php

class Tools
{
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
}
