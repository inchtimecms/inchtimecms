<?php

namespace App\Controller\AdminController;

use App\Form\SystemEntityType;
use App\Repository\SystemEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin")
 */
class SystemEntityController extends AbstractController
{
    /**
     * @Route("/system/entity", name="system_entity")
     */
    public function index(Request $request, SystemEntityRepository $repository, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SYSTEM_ENTITY_VIEW);

        $systemEntity = $repository->find(1);

        $systemForm = $this->createForm( SystemEntityType::class,$systemEntity);

        $systemForm->handleRequest($request);
        if ($systemForm->isSubmitted() && $systemForm->isValid()){
            $systemData = $systemForm->getData();
            $this->denyAccessUnlessGranted(UserPermissionVoter::SYSTEM_ENTITY_EDIT);

            $em->persist($systemData);
            $em->flush();

            return $this->redirectToRoute("system_entity");
        }
        return $this->render('admin_pages/system_entity/index.html.twig', [
            'systemForm' => $systemForm->createView(),
        ]);
    }
}
