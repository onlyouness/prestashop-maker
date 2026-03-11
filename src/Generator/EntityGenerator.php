<?php

namespace Youness\PrestashopMaker\Generator;

use Youness\PrestashopMaker\Generator\AbstractGenerator;
use Youness\PrestashopMaker\Utils\Tools;

class EntityGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type == 'entity';
    }

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

            // Build the Annotation Block
            $annotation = "    /**\n";
            $annotation .= "     * @ORM\Column(name=\"{$fieldName}\", type=\"{$type}\")\n";
            $annotation .= "     */\n";

            if ($config['translatable']) {
                $hasTranslatable = true;
                // Lang Entity logic
                $langProperties .= $annotation;
                $langProperties .= "    private \${$fieldName};\n\n";
                $langMethods .= $this->generateAccessors($pascalField, $fieldName, $phpType);
            } else {
                // Main Entity logic
                $mainProperties .= $annotation;
                $mainProperties .= "    private \${$fieldName};\n\n";
                $mainMethods .= $this->generateAccessors($pascalField, $fieldName, $phpType);
            }
        }

        // --- Handle Translation Logic Block ---
        $translationLogic = "";
        if ($hasTranslatable) {
            $entityName = $data['entity_name'];
            $entityLower = $data['entity_name_lower'];
            $namespace = $data['namespace'];

            $translationLogic = <<<PHP
        /**
         * @var Collection<int, {$entityName}Lang>
         * @ORM\OneToMany(targetEntity="{$namespace}\Entity\\{$entityName}Lang", cascade={"persist", "remove"}, mappedBy="{$entityLower}")
         */
        private \$translations;
    
        /**
         * @return Collection<int, {$entityName}Lang>
         */
        public function getTranslations(): Collection
        {
            return \$this->translations;
        }
    
        public function addTranslation({$entityName}Lang \$translation): self
        {
            if (!\$this->translations->contains(\$translation)) {
                \$this->translations->add(\$translation);
                \$translation->set{$entityName}(\$this);
            }
    
            return \$this;
        }
    
        public function removeTranslation({$entityName}Lang \$translation): self
        {
            \$this->translations->removeElement(\$translation);
            return \$this;
        }
    PHP;

            // Add the constructor to initialize the collection
            $mainMethods = "    public function __construct()\n    {\n        \$this->translations = new ArrayCollection();\n    }\n\n" . $mainMethods;
        }

        // Prepare data for rendering
        $data['properties'] = $mainProperties;
        $data['methods'] = $mainMethods;
        $data['translation_logic'] = $translationLogic;

        // 1. Render Main Entity
        $this->renderAndSave('entity.tpl.php', $this->getModulePath($moduleName) . "/src/Entity/{$data['entity_name']}.php", $data);

        // 2. Render Lang Entity if needed
        if ($hasTranslatable) {
            $data['properties'] = $langProperties;
            $data['methods'] = $langMethods;
            // In the Lang entity, we don't need translation_logic placeholder
            $data['translation_logic'] = "";
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
    /**
     * Helper to keep the code clean
     */
    private function generateAccessors(string $pascalField, string $fieldName, string $phpType): string
    {
        $buffer = "    public function get{$pascalField}(): ?{$phpType}\n";
        $buffer .= "    {\n        return \$this->{$fieldName};\n    }\n\n";
        $buffer .= "    public function set{$pascalField}(?{$phpType} \$value): self\n";
        $buffer .= "    {\n        \$this->{$fieldName} = \$value;\n        return \$this;\n    }\n\n";
        return $buffer;
    }
}
