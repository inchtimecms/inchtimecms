<?php

namespace App\Controller\AdminController;

use App\Entity\ContactFormEntity;
use App\Repository\ContactFormEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/contact/form/entity")
 */
class ContactFormEntityController extends AbstractController
{
    /**
     * @Route("/list", name="contact_form_entity_list")
     */
    public function index(ContactFormEntityRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_ENTITY_VIEW);

        $contactFormEntitys = $repository->findAll();

        $pagination = $paginator->paginate($contactFormEntitys,
            $request->query->getInt('page', 1), 15);

        return $this->render('admin_pages/contact_form_entity/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="contact_form_entity_delete")
     */
    public function deleteContactFormData(ContactFormEntity $contactFormEntity, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTACT_FORM_ENTITY_DELETE);

        $em->remove($contactFormEntity);
        $em->flush();

        return $this->redirectToRoute("contact_form_entity_list");
    }
}
