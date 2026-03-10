<?php


class PsMmm extends Module
{
    public function __construct()
    {
        $this->name          = 'ps_mmm';
        $this->tab           = 'administration';
        $this->version       = '1.0.0';
        $this->author        = 'Prestashop';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('ps_mmm', [], 'Modules.ps_mmm.Admin');
        $this->description = $this->trans('my module description', [], 'Modules.ps_mmm.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    }
}