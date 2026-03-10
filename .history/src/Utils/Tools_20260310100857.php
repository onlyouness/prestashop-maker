<?php

class Tools 
{
    public function toPascalCase(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name;
    }
    public function dd(array $i)
    {
        var_dump([$i]);
        die;
    }
}