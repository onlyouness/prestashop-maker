<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

class <?= $class_name ?> extends Module
{
    public function __construct()
    {
        $this->name = '<?= $name ?>';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = '<?= $author ?>';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('<?= $name ?>', [], 'Modules.<?= $pascaleName ?>.Admin');
        $this->description = $this->trans('<?= $description ?>', [], 'Modules.<?= $pascaleName ?>.Admin');

        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => _PS_VERSION_,
        ];
    }

    public function install(): bool
    {
        return parent::install() 
            <?php if (!empty($hooks)): ?>
            && $this->registerHook([
                <?php foreach ($hooks as $hook): ?>
                '<?= $hook ?>',
                <?php endforeach; ?>
            ])
            <?php endif; ?>
        ;
    }

    public function uninstall(): bool
    {
        return parent::uninstall();
    }

    <?php foreach ($hooks as $hook): ?>
    /**
     * Hook <?= $hook ?>
     */
    public function hook<?= ucfirst($hook) ?>($params)
    {
        // Logic for <?= $hook ?> 
    }

    <?php endforeach; ?>
}