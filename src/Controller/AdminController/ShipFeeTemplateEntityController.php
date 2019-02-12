<?php

namespace App\Controller\AdminController;

use App\Entity\ShipFeeTemplateEntity;
use App\Form\ShipFeeTemplateEntityType;
use App\Repository\ShipFeeTemplateEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @Route("/admin/ship/fee/template")
 */
class ShipFeeTemplateEntityController extends AbstractController
{
    /**
     * @Route("/", name="ship_fee_template_entity_index", methods="GET")
     */
    public function index(Request $request, ShipFeeTemplateEntityRepository $shipFeeTemplateEntityRepository,
                          PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_VIEW);

        $shipFeeTemplateEntitys = $shipFeeTemplateEntityRepository->findAll();
        $pagination = $paginator->paginate(
            $shipFeeTemplateEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/ship_fee_template_entity/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="ship_fee_template_entity_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_NEW);

        return $this->render('admin_pages/ship_fee_template_entity/new.html.twig');
    }

    /**
     * @Route("/new/action", name="ship_fee_template_entity_new_action", methods="GET|POST")
     */
    public function newAction(Request $request):Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_NEW);

        $shipFeeTemplateEntity = new ShipFeeTemplateEntity();
        $shipFeeTemplateEntity->setTemplateName($request->request->get("shipTitle"));
        $shipFeeTemplateEntity->setProvince($request->request->get("province"));
        $shipFeeTemplateEntity->setCity($request->request->get("city"));
        $shipFeeTemplateEntity->setDistrict($request->request->get("district"));
        $shipFeeTemplateEntity->setShipTimeAfterOrder($request->request->get("shiptime"));
        $boolFreeShip = $request->request->get("boolFreeShip");
        $shipFeeTemplateEntity->setShipIsFree($boolFreeShip);

        $shipMethodsArray = array();
        $shipexpress = $request->request->get("shipexpress");
        $shipems = $request->request->get("shipems");
        //如果包邮
        if($boolFreeShip == "1"){
            if($shipexpress == true){
                $shipExpressArray["shipexpress"] = 1;
                array_push($shipMethodsArray,["shipexpress" => $shipExpressArray]);
            }

            if($shipems == true){
                $shipEmsArray["shipems"] = 1;
                array_push($shipMethodsArray,["shipems" =>$shipEmsArray]);
            }

        }else{
            if($shipexpress == true){
                $shipExpressArray = array(
                    "shipexpress" => 1,
                    "defaultnum" => $request->request->get("expressdefaultnum"),
                    "defaultfee" => $request->request->get("expressdefaultfee"),
                    "addnum" => $request->request->get("expressaddnum"),
                    "addfee" => $request->request->get("expressaddfee"),
                );
                array_push($shipMethodsArray,["shipexpress"=>$shipExpressArray]);
            }

            if($shipems == true){
                $shipEmsArray = array(
                    "shipems" => 1,
                    "defaultnum" => $request->request->get("emsdefaultnum"),
                    "defaultfee" => $request->request->get("emsdefaultfee"),
                    "addnum" => $request->request->get("emsaddnum"),
                    "addfee" => $request->request->get("emsaddfee"),
                );
                array_push($shipMethodsArray,["shipems"=>$shipEmsArray]);
            }

        }
        $shipFeeTemplateEntity->setShipMethods($shipMethodsArray);

        $em = $this->getDoctrine()->getManager();
        $em->persist($shipFeeTemplateEntity);
        $em->flush();

        return $this->redirectToRoute("ship_fee_template_entity_index");
    }


    /**
     * @Route("/edit/{id}", name="ship_fee_template_entity_edit", methods="GET|POST")
     * @ParamConverter("shipFeeTemplateEntity", class="App\Entity\ShipFeeTemplateEntity", options={"id" = "id"})
     */
    public function edit(ShipFeeTemplateEntity $shipFeeTemplateEntity): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_EDIT);
        return $this->render('admin_pages/ship_fee_template_entity/edit.html.twig',[
            'shipFeeTemplateEntity' => $shipFeeTemplateEntity
        ]);
    }

    /**
     * @Route("/edit/action/{id}", name="ship_fee_template_entity_edit_action", methods="POST")
     * @ParamConverter("shipFeeTemplateEntity", class="App\Entity\ShipFeeTemplateEntity", options={"id" = "id"})
     */
    public function editAction(Request $request, ShipFeeTemplateEntity $shipFeeTemplateEntity): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_EDIT);
        $shipFeeTemplateEntity->setTemplateName($request->request->get("shipTitle"));
        $shipFeeTemplateEntity->setProvince($request->request->get("province"));
        $shipFeeTemplateEntity->setCity($request->request->get("city"));
        $shipFeeTemplateEntity->setDistrict($request->request->get("district"));
        $shipFeeTemplateEntity->setShipTimeAfterOrder($request->request->get("shiptime"));

        $boolFreeShip = $request->request->get("boolFreeShip");
        $shipMethodsArray = array();
        $shipexpress = $request->request->get("shipexpress");
        $shipems = $request->request->get("shipems");
        //如果包邮
        if($boolFreeShip == "1"){
            if($shipexpress == true){
                $shipExpressArray["shipexpress"] = 1;
                array_push($shipMethodsArray,["shipexpress" => $shipExpressArray]);
            }

            if($shipems == true){
                $shipEmsArray["shipems"] = 1;
                array_push($shipMethodsArray,["shipems" =>$shipEmsArray]);
            }

        }else{
            if($shipexpress == true){
                $shipExpressArray = array(
                    "shipexpress" => 1,
                    "defaultnum" => $request->request->get("expressdefaultnum"),
                    "defaultfee" => $request->request->get("expressdefaultfee"),
                    "addnum" => $request->request->get("expressaddnum"),
                    "addfee" => $request->request->get("expressaddfee"),
                );
                array_push($shipMethodsArray,["shipexpress"=>$shipExpressArray]);
            }

            if($shipems == true){
                $shipEmsArray = array(
                    "shipems" => 1,
                    "defaultnum" => $request->request->get("emsdefaultnum"),
                    "defaultfee" => $request->request->get("emsdefaultfee"),
                    "addnum" => $request->request->get("emsaddnum"),
                    "addfee" => $request->request->get("emsaddfee"),
                );
                array_push($shipMethodsArray,["shipems"=>$shipEmsArray]);
            }

        }
        $shipFeeTemplateEntity->setShipMethods(json_encode($shipMethodsArray));

        $em = $this->getDoctrine()->getManager();
        $em->persist($shipFeeTemplateEntity);
        $em->flush();

        return $this->redirectToRoute("ship_fee_template_entity_index");

    }


    /**
     * @Route("/delete/{id}", name="ship_fee_template_entity_delete", methods="GET")
     * @ParamConverter("shipFeeTemplateEntity", class="App\Entity\ShipFeeTemplateEntity", options={"id" = "id"})
     */
    public function delete(ShipFeeTemplateEntity $shipFeeTemplateEntity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::SHIP_FEE_TEMPLATE_DELETE);

        $em->remove($shipFeeTemplateEntity);
        $em->flush();
        return $this->redirectToRoute('ship_fee_template_entity_index');
    }
}
