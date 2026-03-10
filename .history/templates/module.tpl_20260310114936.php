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

        $this->displayName = $this->trans('{{name}}', [], 'Modules.{{name}}.Admin');
        $this->description = $this->trans('{{description}}', [], 'Modules.{{name}}.Admin');

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
    

        public function install()
        {
            return parent::install() 
                && $this->registerHook([
        <?php foreach ($hooks as $hook): ?>
                    '<?= $hook ?>',
        <?php endforeach; ?>
                ]);
        }

        <?php foreach ($hooks as $hook): ?>
        public function hook<?= ucfirst($hook) ?>($params)
        {
            // Logic for <?= $hook ?> 
        }
        <?php endforeach; ?>
}