<?php

namespace Youness\PrestashopMaker\Generator;

interface GeneratorInterface 
{
    public function supports(string $type) : bool
    {     
    }
}