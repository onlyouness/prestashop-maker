<?php

declare(strict_types=1);

namespace {{namespace}};

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class {{configuration_class_name}} implements DataConfigurationInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

{{config_constants}}

    public function getConfiguration(): array
    {
        $return = [];
{{get_config_lines}}
        return $return;
    }

    public function updateConfiguration(array $configuration): array
    {
        $errors = [];

        if ($this->validateConfiguration($configuration)) {
{{update_config_lines}}
        }

        return $errors;
    }

    public function validateConfiguration(array $configuration): bool
    {
        return isset($configuration[{{validate_config_fields}}]);
    }
}