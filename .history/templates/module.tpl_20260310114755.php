<?php echo "<?php"; ?>

declare(strict_types=1);

class <?= $class_name ?> extends Module
{
    public function __construct()
    {
        $this->name = '<?= $name ?>';
        // ... rest of constructor
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

    <?php foreach ($hooks as $hook): ?>
    public function hook<?= ucfirst($hook) ?>($params)
    {
        // Logic for <?= $hook ?> 
    }
    <?php endforeach; ?>
}