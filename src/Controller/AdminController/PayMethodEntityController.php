<?php

namespace App\Controller\AdminController;

use App\Entity\PayMethodEntity;
use App\Form\PayMethodEntityType;
use App\Repository\PayMethodEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/pay/method/entity")
 */
class PayMethodEntityController extends AbstractController
{
    /**
     * @Route("/", name="pay_method_entity_index", methods="GET")
     */
    public function index(PayMethodEntityRepository $payMethodEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_VIEW);

        return $this->render('admin_pages/pay_method_entity/index.html.twig', [
            'pay_method_entities' => $payMethodEntityRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="pay_method_entity_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_NEW);

        return $this->render("admin_pages/pay_method_entity/new.html.twig");
    }

    /**
     * @Route("/new/action", name="pay_method_entity_new_action", methods="GET|POST")
     */
    public function newAction(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_NEW);

        $payMethodEntity = new PayMethodEntity();
        $payMethodEntity->setPayMethodName($request->request->get("payname"));
        $payMethodEntity->setPayMethodDesc($request->request->get("paydesc"));
        $payMethodEntity->setPayMethodAlias($request->request->get("payalias"));

        $em->persist($payMethodEntity);
        $em->flush();

        return $this->redirectToRoute("pay_method_entity_index");
    }

    /**
     * @Route("/edit/{id}", name="pay_method_entity_edit", methods="GET|POST")
     * @ParamConverter("payMethodEntity", class="App\Entity\PayMethodEntity", options={"id" = "id"})
     */
    public function edit(PayMethodEntity $payMethodEntity): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_EDIT);
        return $this->render('admin_pages/pay_method_entity/edit.html.twig', [
            'payMethodEntity' => $payMethodEntity
        ]);
    }

    /**
     * @Route("/edit/action/{id}", name="pay_method_entity_edit_action", methods="GET|POST")
     * @ParamConverter("payMethodEntity", class="App\Entity\PayMethodEntity", options={"id" = "id"})
     */
    public function editAction(Request $request, PayMethodEntity $payMethodEntity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_EDIT);

        $payMethodEntity->setPayMethodName($request->request->get("payname"));
        $payMethodEntity->setPayMethodDesc($request->request->get("paydesc"));
        $payMethodEntity->setPayMethodAlias($request->request->get("payalias"));

        $em->persist($payMethodEntity);
        $em->flush();

        return $this->redirectToRoute('pay_method_entity_index');
    }

    /**
     * @Route("/delete/{id}", name="pay_method_entity_delete", methods="GET")
     * @ParamConverter("payMethodEntity", class="App\Entity\PayMethodEntity", options={"id" = "id"})
     */
    public function delete(PayMethodEntity $payMethodEntity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PAY_METHOD_ENTITY_DELETE);
        $em->remove($payMethodEntity);
        $em->flush();

        return $this->redirectToRoute('pay_method_entity_index');
    }
}
