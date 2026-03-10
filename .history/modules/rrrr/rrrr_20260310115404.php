<?php

class Rrrr extends Module
{
    public function __construct()
    {
        $this->name          = 'rrrr';
        $this->tab           = 'administration';
        $this->version       = '1.0.0';
        $this->author        = 'Prestashop';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('rrrr', [], 'Modules.rrrr.Admin');
        $this->description = $this->trans('my module description', [], 'Modules.rrrr.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    }
    public function install(): bool
    {
        return parent::install() 
            {{hook_registrations}};
    }

    public function uninstall(): bool   
    {
        return parent::uninstall();
    }

{{hook_methods}}
}