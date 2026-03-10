<?php

class {{class_name}} extends Module
{
    public function __construct()
    {
        $this->name          = '{{name}}';
        $this->tab           = 'administration';
        $this->version       = '1.0.0';
        $this->author        = '{{author}}';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('{{name}}', [], 'Modules.{{class_name}}.Admin');
        $this->description = $this->trans('{{description}}', [], 'Modules.{{class_name}}.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    }
    public function install(): bool
    {
        return parent::install() 
            {{registrations}};
    }

    public function uninstall(): bool
    {
        return parent::uninstall();
    }

{{methods}}
}