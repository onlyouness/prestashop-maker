<?php

use Youness\PrestashopMaker\Generator\AbstractGenerator;

class EntityGenerator extends AbstractGenerator
{
    public function supports(string $type): bool { return $type== 'entity'}
    // Inside EntityGenerator.php

    public function generate(string $moduleName, array $data): void
    {
        $fields = $data['fields'] ?? [];
        $mainProperties = "";
        $mainMethods = "";
        $langProperties = "";
        $langMethods = "";

        $hasTranslatable = false;

        foreach ($fields as $fieldName => $config) {
            $pascalField = \Youness\PrestashopMaker\Utils\Tools::asPascalCase($fieldName);
            $type = $config['type'];
            $phpType = $this->mapToPhpType($type);

            if ($config['translatable']) {
                $hasTranslatable = true;
                // Properties for the Lang Entity
                $langProperties .= "    #[ORM\Column(type: '{$type}')]\n";
                $langProperties .= "    private \${$fieldName};\n\n";

                // Methods for the Lang Entity
                $langMethods .= "    public function get{$pascalField}(): ?{$phpType}\n";
                $langMethods .= "    {\n        return \$this->{$fieldName};\n    }\n\n";
                $langMethods .= "    public function set{$pascalField}({$phpType} \$value): self\n";
                $langMethods .= "    {\n        \$this->{$fieldName} = \$value;\n        return \$this;\n    }\n\n";
            } else {
                // Properties for the Main Entity
                $mainProperties .= "    #[ORM\Column(type: '{$type}')]\n";
                $mainProperties .= "    private \${$fieldName};\n\n";

                // Methods for the Main Entity
                $mainMethods .= "    public function get{$pascalField}(): ?{$phpType}\n";
                $mainMethods .= "    {\n        return \$this->{$fieldName};\n    }\n\n";
                $mainMethods .= "    public function set{$pascalField}({$phpType} \$value): self\n";
                $mainMethods .= "    {\n        \$this->{$fieldName} = \$value;\n        return \$this;\n    }\n\n";
            }
        }

        // 1. Render Main Entity
        $data['properties'] = $mainProperties;
        $data['methods'] = $mainMethods;
        $this->renderAndSave('entity.tpl.php', $this->getModulePath($moduleName) . "/src/Entity/{$data['entity_name']}.php", $data);

        // 2. Render Lang Entity if needed
        if ($hasTranslatable) {
            $data['properties'] = $langProperties;
            $data['methods'] = $langMethods;
            $this->renderAndSave('entity_lang.tpl.php', $this->getModulePath($moduleName) . "/src/Entity/{$data['entity_name']}Lang.php", $data);
        }
    }
    private function mapToPhpType(string $type): string
    {
        return match ($type) {
            'integer' => 'int',
            'boolean' => 'bool',
            'datetime' => '\DateTimeInterface',
            default => 'string',
        };
    }
}
