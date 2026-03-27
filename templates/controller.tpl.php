<?php

declare(strict_types=1);

namespace {{namespace}};

use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\PrestaShopAdminController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class {{controller_class_name}} extends PrestaShopAdminController
{
    public function index(
        Request $request,
        #[Autowire(service: '{{form_handler_service}}')]
        FormHandlerInterface $formDataHandler,
    ): Response {
        $form = $formDataHandler->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $formDataHandler->save($form->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', [], 'Admin.Notifications.Success'));

                return $this->redirectToRoute('{{route_name}}');
            }

            $this->addFlashErrors($errors);
        }

        return $this->render('@Modules/{{module_name}}/views/templates/admin/form.html.twig', [
            '{{form_view_var}}' => $form->createView(),
        ]);
    }
}