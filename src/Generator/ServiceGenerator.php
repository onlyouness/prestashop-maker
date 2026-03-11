<?php


namespace Youness\PrestashopMaker\Generator;

use Symfony\Component\Yaml\Yaml;

class ServiceGenerator extends AbstractGenerator
{
    public function supports(string $type): bool
    {
        return $type === 'service';
    }

    public function generate(string $moduleName, array $data): void
    {
        $servicesYamlPath = $this->getModulePath($moduleName) . '/config/services.yml';
        
        // 1. Ensure directory exists
        if (!is_dir(dirname($servicesYamlPath))) {
            $this->filesystem->mkdir(dirname($servicesYamlPath));
        }

        // 2. Load existing YAML or start a new one
        $services = [];
        if (file_exists($servicesYamlPath)) {
            $services = Yaml::parseFile($servicesYamlPath) ?? [];
        }

        if (!isset($services['services'])) {
            $services['services'] = [
                '_defaults' => [
                    'autowire' => true,
                    'autoconfigure' => true,
                    'public' => true,
                ]
            ];
        }

        $serviceId = $data['service_id'];
        $services['services'][$serviceId] = $data['definition'];

        $newYaml = Yaml::dump($services, 4);
        $this->filesystem->dumpFile($servicesYamlPath, $newYaml);
    }
}