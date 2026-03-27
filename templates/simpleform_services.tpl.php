services:
  _defaults:
    public: true

  {{controller_fqcn}}:
    autowire: true
    autoconfigure: true

  prestashop.module.{{module_name}}.form.type.{{form_name_lower}}_form_type:
    class: '{{namespace}}\Form\{{form_name}}FormType'
    parent: 'form.type.translatable.aware'
    public: true
    tags:
      - { name: form.type }

  prestashop.module.{{module_name}}.form.{{form_name_lower}}_data_configuration:
    class: '{{namespace}}\Form\{{form_name}}DataConfiguration'
    arguments: ['@prestashop.adapter.legacy.configuration']

  prestashop.module.{{module_name}}.form.{{form_name_lower}}_form_data_provider:
    class: '{{namespace}}\Form\{{form_name}}FormDataProvider'
    arguments:
      - '@prestashop.module.{{module_name}}.form.{{form_name_lower}}_data_configuration'

  prestashop.module.{{module_name}}.form.{{form_name_lower}}_form_data_handler:
    class: 'PrestaShop\PrestaShop\Core\Form\Handler'
    arguments:
      - '@form.factory'
      - '@prestashop.core.hook.dispatcher'
      - '@prestashop.module.{{module_name}}.form.{{form_name_lower}}_form_data_provider'
      - '{{namespace}}\Form\{{form_name}}FormType'
      - '{{form_name}}Configuration'