<?php

namespace App\Controller\AdminController;

use App\Entity\TaxonomyTypeEntity;
use App\Form\TaxonomyTypeEntityType;
use App\Repository\TaxonomyTypeEntityRepository;
use App\Security\Voter\TaxonomyTypeEntityVoter;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/taxonomy/type/entity")
 */
class TaxonomyTypeEntityController extends AbstractController
{
    /**
     * @Route("/list", name="taxonomy_type_entity_index", methods="GET")
     */
    public function index(Request $request, TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_VIEW);

        $taxonomyTypeEntitys = $taxonomyTypeEntityRepository->findAll();

        $pagination = $paginator->paginate(
            $taxonomyTypeEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/taxonomy_type_entity/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/addPage", name="taxonomy_type_entity_addPage", methods="GET|POST")
     */
    public function taxonomyTypeAddPage():Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_NEW);

        return $this->render("admin_pages/taxonomy_type_entity/new.html.twig");
    }

    /**
     * 添加分类标签，添加完成后，转到添加分类词汇列表页面显示当前标签下的所有词汇
     * @Route("/addAction", name="taxonomy_type_entity_add", methods="POST")
     */
    public function taxonomyTypeAdd(Request $request):Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_NEW);

        $newTaxonomyTypeName = $request->request->get("newTaxonomyTypeName");
        $newTaxonomyTypeAlias = $request->request->get("newTaxonomyTypeMachineAlias");
        $newTaxonomyTypeDesc = $request->request->get("newTaxonomyTypeDescription");

        $em = $this->getDoctrine()->getManager();

        $taxonomyTypeEntity = new TaxonomyTypeEntity();
        $taxonomyTypeEntity->setTaxonomyName($newTaxonomyTypeName);
        $taxonomyTypeEntity->setTaxonomyDesc($newTaxonomyTypeDesc);
        $taxonomyTypeEntity->setTaxonomyAlias($newTaxonomyTypeAlias);

        $em->persist($taxonomyTypeEntity);
        $em->flush();

        return $this->redirectToRoute("taxonomy_entity_index",[
            "taxonomyTypeEntity_Id" => $taxonomyTypeEntity->getId()
        ]);
    }

    /**
     * 编辑分类标签页面
     * @Route("/editPage/{taxonomyTypeEntity_Id}", name="taxonomy_type_entity_editPage", methods="GET|POST")
     * @ParamConverter("taxonomyTypeEntity", class="App\Entity\TaxonomyTypeEntity", options={"id" = "taxonomyTypeEntity_Id"})
     */
    public function editTaxonomyTypePage(TaxonomyTypeEntity $taxonomyTypeEntity):Response
    {
        $this->denyAccessUnlessGranted(TaxonomyTypeEntityVoter::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT,$taxonomyTypeEntity);
        return $this->render("admin_pages/taxonomy_type_entity/edit.html.twig",[
            "taxonomyTypeEntity" => $taxonomyTypeEntity
        ]);
    }

    /**
     * 编辑分类标签
     * @Route("/editAction/{taxonomyTypeEntity_Id}", name="taxonomy_type_entity_editAction", methods="GET|POST")
     * @ParamConverter("taxonomyTypeEntity", class="App\Entity\TaxonomyTypeEntity", options={"id" = "taxonomyTypeEntity_Id"})
     */
    public function editTaxonomyTypeAction(Request $request, TaxonomyTypeEntity $taxonomyTypeEntity):Response
    {
        $this->denyAccessUnlessGranted(TaxonomyTypeEntityVoter::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_EDIT,$taxonomyTypeEntity);

        $newTaxonomyTypeName = $request->request->get("newTaxonomyTypeName");
        $newTaxonomyTypeDesc = $request->request->get("newTaxonomyTypeDescription");

        $taxonomyTypeEntity->setTaxonomyName($newTaxonomyTypeName);
        $taxonomyTypeEntity->setTaxonomyDesc($newTaxonomyTypeDesc);
        $em = $this->getDoctrine()->getManager();
        $em->persist($taxonomyTypeEntity);
        $em->flush();

        return $this->redirectToRoute("taxonomy_type_entity_index");
    }

    /**
     * 删除分类标签
     * @Route("/delete/{taxonomyTypeEntity_Id}", name="taxonomy_type_entity_deleteAction", methods="GET|POST")
     * @ParamConverter("taxonomyTypeEntity", class="App\Entity\TaxonomyTypeEntity", options={"id" = "taxonomyTypeEntity_Id"})
     */
    public function deleteTaxonomyTypeAction(TaxonomyTypeEntity $taxonomyTypeEntity, EntityManagerInterface $em):Response
    {
        $this->denyAccessUnlessGranted(TaxonomyTypeEntityVoter::TAXONOMY_TYPE_ENTITY_TAXONOMY_TYPE_ALIAS_DELETE, $taxonomyTypeEntity);

        $taxonomyEntitys = $taxonomyTypeEntity->getTaxonomyEntitys();
        foreach($taxonomyEntitys as $taxonomyEntity)
        {
            $em->remove($taxonomyEntity);
        }

        $em->remove($taxonomyTypeEntity);

        $em->flush();

        return $this->redirectToRoute("taxonomy_type_entity_index");
    }


    /**
     * 判断分类标签的机器名是否重复,不重复返回0 重复返回1
     * @Route("/alias_unique", name="taxonomy_type_entity_aliasIsExists", methods="POST")
     */
    public function aliasIsExists(Request $request, TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository):Response
    {
        $newAlias = $request->request->get("field_alias");
        $taxonomyTypeEntity = $taxonomyTypeEntityRepository->findOneBy(array('taxonomyAlias' => $newAlias));

        return new Response($taxonomyTypeEntity == null ? "0" : "1");
    }


    /**
     * 输出json格式，所有的分类标签，用于内容类型添加 分类标签 类型的字段时ajax插入DOM元素
     * @Route("/getAll_json", name="taxonomy_type_entity_getAllJson", methods="GET|POST")
     */
    public function getAllJson(TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository):Response
    {
        $taxonomyTypeEntitys = $taxonomyTypeEntityRepository->findAll();

        $ajaxTaxonomyTypeResults = Array();
        foreach ($taxonomyTypeEntitys as $taxonomyTypeEntity)
        {
            $ajaxTaxonomyTypeResult = array(
                "id" => $taxonomyTypeEntity->getId(),
                "typeName" => $taxonomyTypeEntity->getTaxonomyName(),
                "typeDesc" => $taxonomyTypeEntity->getTaxonomyDesc(),
                "taxonomyTypeAlias" => $taxonomyTypeEntity->getTaxonomyAlias()
            );

            array_push($ajaxTaxonomyTypeResults, $ajaxTaxonomyTypeResult);
        }

        //把$fileManagedEntity以json串返回去。
        $json_response = new JsonResponse($ajaxTaxonomyTypeResults);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;
    }
}
