<?php
namespace Youness\PrestashopMaker\Generator;

use Youness\PrestashopMaker\Generator\FileGenerator;
use Youness\PrestashopMaker\Template\TemplateRender;

class Generator
{
    public function generate(string $file_name, array $data)
    {
        $content   = (new TemplateRender())->render($file_name . '.tpl.php', $data);
        (new FileGenerator())->handle(__DIR__ . '/../../modules', $file_name,$content);
    }
}
