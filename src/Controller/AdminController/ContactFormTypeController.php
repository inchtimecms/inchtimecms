<?php

namespace App\Controller\AdminController;

use App\Entity\ContactFormFieldEntity;
use App\Entity\ContactFormTypeEntity;
use App\Form\CustomContactFormType;
use App\Repository\ContactFormTypeEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/contact/form/type")
 */
class ContactFormTypeController extends AbstractController
{
    /**
     * @Route("/list", name="contact_form_type_list")
     */
    public function index(ContactFormTypeEntityRepository $repository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_TYPE_ENTITY_VIEW);
        $contactFormTypeEntitys = $repository->findAll();

        return $this->render('admin_pages/contact_form_type/index.html.twig', [
            "contactFormTypeEntitys" => $contactFormTypeEntitys,
        ]);
    }

    /**
     * @Route("/new", name="contact_form_type_new")
     */
    public function newContactFormType(Request $request,EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_TYPE_ENTITY_NEW);

        $contactFormTypeEntity = new ContactFormTypeEntity();

        $contactFormField = new ContactFormFieldEntity();
        $contactFormField->setFormFieldLabel("");
        $contactFormTypeEntity->getContactFormFieldEntities()->add($contactFormField);

        $newContactForm = $this->createForm(CustomContactFormType::class,$contactFormTypeEntity);

        $newContactForm->handleRequest($request);

        if ($newContactForm->isSubmitted() && $newContactForm->isValid()) {

            /**@var ContactFormTypeEntity $contactFormTypeData **/
            $contactFormTypeData = $newContactForm->getNormData();

            $contactFormTypeData->setCreateAt(new \DateTime());

            $contactFormFieldEntitys = $contactFormTypeData->getContactFormFieldEntities();
            foreach ($contactFormFieldEntitys as $contactFormFieldEntity){
                $contactFormFieldEntity->setContactFormTypeEntity($contactFormTypeData);
                $em->persist($contactFormFieldEntity);
            }

            $em->persist($contactFormTypeData);

            $em->flush();


            return $this->redirectToRoute('contact_form_type_list');
        }


        return $this->render('admin_pages/contact_form_type/new.html.twig', [
            "newContactForm" => $newContactForm->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="contact_form_type_edit")
     */
    public function editContactFormType(ContactFormTypeEntity $contactFormTypeEntity, Request $request,
                                       EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_TYPE_ENTITY_EDIT);

        $editCustomForm = $this->createForm(CustomContactFormType::class,$contactFormTypeEntity);

        foreach($contactFormTypeEntity->getContactFormFieldEntities() as $formField){
            $em->remove($formField);
        }

        $editCustomForm->handleRequest($request);

        if ($editCustomForm->isSubmitted() && $editCustomForm->isValid()) {

            /**
             * @var ContactFormTypeEntity $contactFormTypeData
             */
            $contactFormTypeData = $editCustomForm->getData();

            $contactFormFieldEntitys = $contactFormTypeData->getContactFormFieldEntities();
            foreach ($contactFormFieldEntitys as $contactFormFieldEntity){
                $contactFormFieldEntity->setContactFormTypeEntity($contactFormTypeData);
                $em->persist($contactFormFieldEntity);
            }

            $em->persist($contactFormTypeData);
            $em->flush();

            return $this->redirectToRoute('contact_form_type_list');
        }


        return $this->render('admin_pages/contact_form_type/edit.html.twig', [
            "editCustomForm" => $editCustomForm->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="contact_form_type_delete")
     */
    public function deleteContactFormType(ContactFormTypeEntity $contactFormTypeEntity,EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_TYPE_ENTITY_DELETE);

        $contactFormEntitys = $contactFormTypeEntity->getContactFormEntitys();
        foreach($contactFormEntitys as $contactFormEntity){
            $em->remove($contactFormEntity);
        }
        $em->remove($contactFormTypeEntity);
        $em->flush();

        return $this->redirectToRoute("contact_form_type_list");
    }

}
