<?php

namespace App\Controller\CozaController;

use App\Entity\ContactFormEntity;
use App\Entity\ContactFormTypeEntity;
use App\Form\ContactFormObjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactFormController extends BaseController
{
    /**
     * @Route("/form/{formTypeAlias}", name="contact_form_show")
     */
    public function showContactForm(Request $request, ContactFormTypeEntity $contactFormTypeEntity, EntityManagerInterface $em)
    {

        $form = $this->createForm(ContactFormObjectType::class, [],
            ["contact_form_type_id" => $contactFormTypeEntity->getId()]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $formFields = $contactFormTypeEntity->getContactFormFieldEntities();

            for ($i = 0; $i < sizeof($formFields); $i++) {
                $index = $i + 1;
                $formValue = $formData["label" . $index];
                $formEntityDataArray[$formFields[$i]->getFormFieldLabel()] = $formValue;
            }

            $contactFormEntity = new ContactFormEntity();
            $contactFormEntity->setContactFormTypeEntity($contactFormTypeEntity);
            $contactFormEntity->setContactFormData($formEntityDataArray);
            $contactFormEntity->setCreateAt(new \DateTime());

            $em->persist($contactFormEntity);
            $em->flush();

            return $this->redirectToRoute("index");
        }

        return $this->render("themes/cozastore/pages/contact.html.twig", [
            "contactFormTypeEntity" => $contactFormTypeEntity,
            "contactForm" => $form->createView(),
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this
        ]);
    }

}
