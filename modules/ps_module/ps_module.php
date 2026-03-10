<?php

namespace Prestashop\PsModule;


class PsModule extends Module
{
    public function __construct()
    {
        $this->name          = 'ps_module';
        $this->tab           = 'administration';
        $this->version       = '1.0.0';
        $this->author        = 'Prestashop';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('ps_module', [], 'Modules.ps_module.Admin');
        $this->description = $this->trans('my module description', [], 'Modules.ps_module.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    }
}