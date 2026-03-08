<?php
namespace Youness\PrestashopMaker\Template;
class TemplateRender
{

    public function render(string $template, array $variables): string
    {
        $content = file_get_contents(__DIR__ . "/../../templates/$template");

        foreach ($variables as $key => $value) {
            $content = str_replace("{{".$key."}}", $value, $content);
        }

        return $content;
    }
}
