<?php

declare(strict_types=1);

namespace {{namespace}};

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
{{use_statements}}

class {{form_class_name}} extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
{{form_fields}}        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit.html.twig',
        ]);
    }
}