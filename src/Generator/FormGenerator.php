<?php

namespace Youness\PrestashopMaker\Generator;

use Youness\PrestashopMaker\Utils\Tools;

class FormGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type == 'form';
    }

    public function generate(string $moduleName, array $data): void
    {
        $fields = $data['fields'] ?? [];
        $formName = $data['form_name'];
        $namespace = $data['namespace'];

        // --- Build form fields for the FormType ---
        $formFields = "";
        $useStatements = "use Symfony\\Component\\Form\\FormBuilderInterface;\nuse Symfony\\Component\\OptionsResolver\\OptionsResolver;\n";
        $usedTypes = [];
        $hasTranslatable = false;

        foreach ($fields as $fieldName => $config) {

            $symfonyType = $this->mapToSymfonyFormType($config['type']);
            $typeClass = $this->getTypeClassName($symfonyType);

            if ($config['translatable']) {
                $hasTranslatable = true;

                // Add the inner type use statement
                if (!in_array($symfonyType, $usedTypes)) {
                    $usedTypes[] = $symfonyType;
                    $useStatements .= "use {$symfonyType};\n";
                }

                $options = [];
                $options[] = "'label' => \$this->trans('" . ucfirst(str_replace('_', ' ', $fieldName)) . "', 'Modules." . Tools::asPascalCase($moduleName) . ".Admin')";
                $options[] = "'type' => {$typeClass}::class";
                if (!$config['required']) {
                    $options[] = "'required' => false";
                }

                $optionsStr = implode(",\n                ", $options);

                $formFields .= "            ->add('{$fieldName}', TranslatableType::class, [\n                {$optionsStr},\n            ])\n";
            } else {
                if (!in_array($symfonyType, $usedTypes)) {
                    $usedTypes[] = $symfonyType;
                    $useStatements .= "use {$symfonyType};\n";
                }

                $options = [];
                $options[] = "'label' => \$this->trans('" . ucfirst(str_replace('_', ' ', $fieldName)) . "', 'Modules." . Tools::asPascalCase($moduleName) . ".Admin')";
                if (!$config['required']) {
                    $options[] = "'required' => false";
                }

                $optionsStr = implode(",\n                ", $options);


                $formFields .= "            ->add('{$fieldName}', {$typeClass}::class, [\n                {$optionsStr},\n            ])\n";
            }
        }

        if ($hasTranslatable) {
            $useStatements .= "use PrestaShopBundle\\Form\\Admin\\Type\\TranslatableType;\n";
        }

        // --- Build configuration constants and get/set logic ---
        $configConstants = "";
        $getConfigLines = "";
        $updateConfigLines = "";
        $validateConfigLines = "";

        $configPrefix = strtoupper($moduleName) . '_';

        foreach ($fields as $fieldName => $config) {
            $constName = $configPrefix . strtoupper($fieldName);
            $configConstants .= "    public const {$constName} = '{$constName}';\n";
            $getConfigLines .= "        \$return['{$fieldName}'] = \$this->configuration->get(static::{$constName});\n";
            $updateConfigLines .= "        \$this->configuration->set(static::{$constName}, \$configuration['{$fieldName}']);\n";
            $validateConfigLines .= "'{$fieldName}', ";
        }

        $validateConfigFields = rtrim($validateConfigLines, ', ');

        // Prepare template data
        $formData = array_merge($data, [
            'form_class_name' => $formName . 'FormType',
            'use_statements' => $useStatements,
            'form_fields' => $formFields,
            'namespace' => $namespace . '\\Form',
            'module_pascal' => Tools::asPascalCase($moduleName),
        ]);

        // TODO: add if long text the html array to be value true in set 

        $configData = array_merge($data, [
            'configuration_class_name' => $formName . 'DataConfiguration',
            'namespace' => $namespace . '\\Form',
            'config_constants' => $configConstants,
            'get_config_lines' => $getConfigLines,
            'update_config_lines' => $updateConfigLines,
            'validate_config_fields' => $validateConfigFields,
        ]);

        $providerData = array_merge($data, [
            'data_provider_configuration_class_name' => $formName . 'FormDataProvider',
            'configuration_class_name' => $formName . 'DataConfiguration',
            'namespace' => $namespace . '\\Form',
        ]);

        $controllerData = array_merge($data, [
            'controller_class_name' => $formName . 'ConfigurationController',
            'namespace' => $namespace . '\\Controller',
            'form_handler_service' => 'prestashop.module.' . $moduleName . '.form.' . strtolower($formName) . '_form_data_handler',
            'form_view_var' => lcfirst($formName) . 'Form',
            'route_name' => $moduleName . '_configuration_' . strtolower($formName),
            'module_name' => $moduleName,
            'form_name' => $formName,
        ]);

        $twigData = array_merge($data, [
            'form_view_var' => lcfirst($formName) . 'Form',
            'form_title' => $formName . ' Configuration',
            'module_pascal' => Tools::asPascalCase($moduleName),
            'module_name' => $moduleName,
        ]);

        $routeData = array_merge($data, [
            'route_name' => $moduleName . '_configuration_' . strtolower($formName),
            'route_path' => '/' . $moduleName . '/configuration',
            'controller_fqcn' => $namespace . '\\Controller\\' . $formName . 'ConfigurationController',
            'legacy_controller' => 'Admin' . Tools::asPascalCase($moduleName) . $formName,
        ]);

        // 1. Render FormType
        $this->renderAndSave('form.tpl.php', $this->getModulePath($moduleName) . "/src/Form/{$formName}FormType.php", $formData);

        // 2. Render DataConfiguration
        $this->renderAndSave('data_configuration.tpl.php', $this->getModulePath($moduleName) . "/src/Form/{$formName}DataConfiguration.php", $configData);

        // 3. Render FormDataProvider
        $this->renderAndSave('data_provider_configuration.tpl.php', $this->getModulePath($moduleName) . "/src/Form/{$formName}FormDataProvider.php", $providerData);

        // 4. Render Controller
        $this->renderAndSave('controller.tpl.php', $this->getModulePath($moduleName) . "/src/Controller/{$formName}ConfigurationController.php", $controllerData);

        // 5. Render Twig template
        $this->renderAndSave('form_view.tpl.php', $this->getModulePath($moduleName) . "/views/templates/admin/form.html.twig", $twigData);

        // 6. Render routes.yml
        $this->renderAndSave('routes.tpl.php', $this->getModulePath($moduleName) . "/config/routes.yml", $routeData);

        // 7. Render services.yml
        $serviceData = array_merge($data, [
            'controller_fqcn' => $namespace . '\\Controller\\' . $formName . 'ConfigurationController',
            'module_name' => $moduleName,
            'form_name' => $formName,
            'form_name_lower' => strtolower($formName),
            'namespace' => $namespace,
        ]);
        $this->renderAndSave('simpleform_services.tpl.php', $this->getModulePath($moduleName) . "/config/services.yml", $serviceData);
    }

    private function mapToSymfonyFormType(string $type): string
    {
        return match ($type) {
            'integer' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\IntegerType',
            // 'text' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType',
            'text' => 'PrestaShopBundle\\Component\\Admin\\Type\\FormattedTextareaType',
            'choice' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType',
            'datetime' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\DateTimeType',
            default => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
        };
    }

    private function getTypeClassName(string $fqcn): string
    {
        $parts = explode('\\', $fqcn);
        return end($parts);
    }
}
