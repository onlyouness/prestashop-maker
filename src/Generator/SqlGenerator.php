<?php

namespace Youness\PrestashopMaker\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Youness\PrestashopMaker\Utils\Tools;

class SqlGenerator extends AbstractGenerator
{
    public function __construct(
        protected string $dbPrefix,
        protected string $modulesDir,
        protected string $mysqlEngine,
    ) {
        $this->filesystem = new Filesystem();
    }
    public function generate(string $moduleName, array $data): void
    {
        $entityName = strtolower($data['entity_name']);
        $tableName = strtolower($moduleName . '_' . $entityName);

        // --- 1. Main Table Construction ---
        $mainColumns = [];
        $mainColumns[] = "  `id_{$entityName}` int(11) NOT NULL AUTO_INCREMENT";

        foreach ($data['fields'] as $name => $value) {
            if (!((bool)($value['translatable'] ?? false))) {
                $type = $this->getSqlType($value['type'], $value['nullable'] ?? false);
                $mainColumns[] = "  `{$name}` {$type}";
            }
        }

        $mainColumns[] = "  PRIMARY KEY (`id`)";

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->dbPrefix}{$tableName}` (\n";
        $sql .= implode(",\n", $mainColumns);
        $sql .= "\n) ENGINE={$this->mysqlEngine} DEFAULT CHARSET=UTF8;\n\n";

        // --- 2. Lang Table Construction (If Translatable) ---
        $langFields = array_filter($data['fields'], fn($f) => (bool)($f['translatable'] ?? false));

        if (!empty($langFields)) {
            $langColumns = [];
            $langColumns[] = "  `id_{$entityName}` int(11) NOT NULL";
            $langColumns[] = "  `id_lang` int(11) NOT NULL";

            foreach ($langFields as $name => $value) {
                $type = $this->getSqlType($value['type'], $value['nullable'] ?? false);
                $langColumns[] = "  `{$name}` {$type}";
            }

            // Composite Primary Key for PrestaShop standards
            $langColumns[] = "  PRIMARY KEY (`id_{$entityName}`, `id_lang`)";

            $sql .= "CREATE TABLE IF NOT EXISTS `{$this->dbPrefix}{$tableName}_lang` (\n";
            $sql .= implode(",\n", $langColumns);
            $sql .= "\n) ENGINE={$this->mysqlEngine} DEFAULT CHARSET=UTF8;";
        }
        // Tools::dd([$sql]);

        $target = $this->getModulePath($moduleName) . "/sql/install.sql";
        $this->filesystem->dumpFile($target, $sql);
    }



    private function getSqlType(string $type, bool $nullable): string
    {
        $nullable = $nullable ? 'NULL' : 'NOT NULL';
        return match ($type) {
            'integer'  => "int(11) $nullable",
            'boolean'  => "tinyint(1) $nullable",
            'text'     => "text $nullable",
            'datetime' => "datetime $nullable",
            default    => "varchar(255) $nullable",
        };
    }

    public function supports(string $type): bool
    {
        return $type === 'sql';
    }
}
