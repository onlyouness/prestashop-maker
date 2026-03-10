<?php

class PsPos extends Module
{
    public function __construct()
    {
        $this->name          = 'ps_pos';
        $this->tab           = 'administration';
        $this->version       = '1.0.0';
        $this->author        = 'Prestashop';
        $this->need_instance = 0;
        $this->bootstrap     = true;

        parent::__construct();

        $this->displayName = $this->trans('ps_pos', [], 'Modules.PsPos.Admin');
        $this->description = $this->trans('my module description', [], 'Modules.PsPos.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    }
    public function install(): bool
    {
        return parent::install()
            && $this->registerHook([
                'displayHeader',
            ]);
    }

    public function uninstall(): bool
    {
        return parent::uninstall();
    }


    public function hookDisplayHeader($params)
    {
        // Logic for displayHeader
    }
}
