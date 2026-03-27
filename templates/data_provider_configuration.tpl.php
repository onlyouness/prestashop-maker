<?php

declare(strict_types=1);

namespace {{namespace}};

use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class {{data_provider_configuration_class_name}} implements FormDataProviderInterface
{
    private {{configuration_class_name}} $dataConfiguration;

    public function __construct({{configuration_class_name}} $dataConfiguration)
    {
        $this->dataConfiguration = $dataConfiguration;
    }

    public function getData(): array
    {
        return $this->dataConfiguration->getConfiguration();
    }

    public function setData(array $data): array
    {
        return $this->dataConfiguration->updateConfiguration($data);
    }
}