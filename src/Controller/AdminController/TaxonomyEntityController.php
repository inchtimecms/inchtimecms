<?php

namespace App\Controller\AdminController;

use App\Entity\TaxonomyEntity;
use App\Entity\TaxonomyTypeEntity;
use App\Form\TaxonomyEntityType;
use App\Repository\TaxonomyEntityRepository;
use App\Repository\TaxonomyTypeEntityRepository;
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
 * @Route("/admin/taxonomy/entity")
 */
class TaxonomyEntityController extends AbstractController
{
    /**
     * @Route("/{taxonomyTypeEntity_Id}", requirements={"taxonomyTypeEntity_Id": "[1-9]\d*"},name="taxonomy_entity_index", methods="GET")
     */
    public function index(Request $request, int $taxonomyTypeEntity_Id, PaginatorInterface $paginator,
                          TaxonomyEntityRepository $taxonomyEntityRepository, TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository): Response
    {

        $taxonomyEntitys = $taxonomyEntityRepository->findBy(array("taxonomyTypeEntity"=>$taxonomyTypeEntity_Id));

        $pagination = $paginator->paginate(
            $taxonomyEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/taxonomy_entity/index.html.twig', [
            "pagination" => $pagination,
            "taxonomyTypeEntity_Id" => $taxonomyTypeEntity_Id,
            "taxonomyTypeEntity" => $taxonomyTypeEntityRepository->find($taxonomyTypeEntity_Id),
        ]);
    }

    /**
     * 添加分类词汇先选择分类标签
     * @Route("/add_page", name="taxonomy_entity_add_choice", methods="GET|POST")
     */
    public function choiceTaxonomyType(TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository):Response
    {
        $taxonomyTypeEntitys = $taxonomyTypeEntityRepository->findAll();
        return $this->render("admin_pages/taxonomy_entity/taxonomyTypeList.html.twig",[
            "taxonomyTypeEntitys" => $taxonomyTypeEntitys
        ]);
    }

    /**
     * 添加某分类标签下的词汇，转到表单页面
     * @Route("/add_form/{taxonomyTypeEntity_Id}", name="taxonomy_entity_new_page", methods="GET|POST")
     */
    public function addTaxonomyEntity(int $taxonomyTypeEntity_Id):Response
    {

        return $this->render("admin_pages/taxonomy_entity/new.html.twig",[
            "taxonomyTypeEntity_Id" => $taxonomyTypeEntity_Id
        ]);
    }

    /**
     * 添加分类词汇表单的action
     * @Route("/add_word_action/{taxonomyTypeEntity_Id}", name="taxonomy_entity_add_action", methods="POST")
     * @ParamConverter("taxonomyTypeEntity", class="App\Entity\TaxonomyTypeEntity", options={"id" = "taxonomyTypeEntity_Id"})
     */
    public function addTaxonomyEntityAction(Request $request, int $taxonomyTypeEntity_Id, TaxonomyTypeEntity $taxonomyTypeEntity):Response
    {

        $newTaxonomyWord = $request->request->get("newTaxonomyWord");
        $newTaxonomyDesc = $request->request->get("newTaxonomyDesc");

        $taxonomyEntity = new TaxonomyEntity();
        $taxonomyEntity->setTaxonomyWord($newTaxonomyWord);
        $taxonomyEntity->setTaxonomyDesc($newTaxonomyDesc);
        $taxonomyEntity->setChangedAt(new \DateTime());
        $taxonomyEntity->setWeight(0);
        $taxonomyEntity->setTaxonomyTypeEntity($taxonomyTypeEntity);

        $em = $this->getDoctrine()->getManager();
        $em->persist($taxonomyEntity);
        $em->flush();

        return $this->redirectToRoute("taxonomy_entity_index",[
            "taxonomyTypeEntity_Id"=>$taxonomyTypeEntity_Id
        ]);
    }

    /**
     * 显示分类词汇表单页面
     * @Route("/edit_form/{taxonomyEntity_Id}", name="taxonomy_entity_edit_page", methods="GET|POST")
     * @ParamConverter("taxonomyEntity", class="App\Entity\TaxonomyEntity", options={"id" = "taxonomyEntity_Id"})
     */
    public function editTaxonomyEntity(TaxonomyEntity $taxonomyEntity):Response
    {
        return $this->render("admin_pages/taxonomy_entity/edit.html.twig",[
            "taxonomyEntity" => $taxonomyEntity
        ]);
    }

    /**
     * @Route("/edit_action/{taxonomyEntity_Id}", name="taxonomy_entity_edit_action", methods="POST")
     * @ParamConverter("taxonomyEntity", class="App\Entity\TaxonomyEntity", options={"id" = "taxonomyEntity_Id"})
     */
    public function editTaxonomyEntityAction(TaxonomyEntity $taxonomyEntity, Request $request):Response
    {

        $editedTaxonomyWord = $request->request->get("newTaxonomyWord");
        $editedTaxonomyDesc = $request->request->get("newTaxonomyDesc");

        $taxonomyEntity->setTaxonomyWord($editedTaxonomyWord);
        $taxonomyEntity->setTaxonomyDesc($editedTaxonomyDesc);
        $taxonomyEntity->setChangedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($taxonomyEntity);
        $em->flush();

        return $this->redirectToRoute("taxonomy_entity_index",[
            "taxonomyTypeEntity_Id"=>$taxonomyEntity->getTaxonomyTypeEntity()->getId()
        ]);
    }

    /**
     * @Route("/delete_action/{taxonomyEntity_Id}", name="taxonomy_entity_delete_action", methods="GET|POST")
     * @ParamConverter("taxonomyEntity", class="App\Entity\TaxonomyEntity", options={"id" = "taxonomyEntity_Id"})
     */
    public function deleteTaxonomyEntityAction(TaxonomyEntity $taxonomyEntity):Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($taxonomyEntity);
        $em->flush();

        return $this->redirectToRoute("taxonomy_entity_index",[
            "taxonomyTypeEntity_Id"=>$taxonomyEntity->getTaxonomyTypeEntity()->getId()
        ]);
    }

    /**
     * 添加内容时，当input 为引用 分类标签 时，获取相关TaxonomyEntity, 返回分类词汇和id JSON串
     * @Route("/fetch_taxonomy_action", name="fetch_taxonomy_action",methods="POST")
     */
    public function fetchTaxonomyAction(Request $request, EntityManagerInterface $em):Response
    {
        //获取当前字段引用的标签类型
        $taxonomyTypeIds = $request->request->get("field_ref_taxonomyTypeIds");
        //获取当前输入的关键字
        $keywords = $request->request->get("field_ref_taxonomyKeyWords");

        //$em = $this->getDoctrine()->getManager();

        $query_dql = 'SELECT t FROM App\Entity\TaxonomyEntity t WHERE t.taxonomyWord LIKE :keywords AND t.taxonomyTypeEntity IN (:taxonomyTypeIds)';
        $query = $em->createQuery($query_dql);
        $query->setParameter('keywords', '%'.$keywords.'%');
        $query->setParameter('taxonomyTypeIds', $taxonomyTypeIds);
        $taxonomyEntitys = $query->getResult();

        $taxonomyEntityResults = Array();
        foreach($taxonomyEntitys as $taxonomyEntity)
        {
            $array = array(
                "taxonomy_id" => $taxonomyEntity->getId(),
                "taxonomy_word" => $taxonomyEntity->getTaxonomyWord()
            );
            array_push($taxonomyEntityResults,$array);
        }

        //把$fileManagedEntity以json串返回去。
        $json_response = new JsonResponse($taxonomyEntityResults);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;
    }

    /**
     * 添加内容页面，ajax添加分类词汇
     * @Route("/ajax_add_taxonomy/{taxonomyTypeEntity_Id}", name="ajax_add_taxonomy", methods="POST")
     * @ParamConverter("taxonomyTypeEntity", class="App\Entity\TaxonomyTypeEntity", options={"id" = "taxonomyTypeEntity_Id"})
     */
    public function addTaxonomyWordsAjax(Request $request, TaxonomyTypeEntity $taxonomyTypeEntity)
    {
        //post请求的参数 1：词汇组,  2：分类标签id,
        $postTaxonomyWords = $request->request->get("postTaxonomyWords");

        $postTaxonomyWordsArray = explode(" ",$postTaxonomyWords);

        $resultJsonArray = Array();
        foreach($postTaxonomyWordsArray as $postTaxonomyWord)
        {
            $taxonomyEntity = new TaxonomyEntity();
            $taxonomyEntity->setTaxonomyWord($postTaxonomyWord);
            $taxonomyEntity->setTaxonomyDesc("");
            $taxonomyEntity->setChangedAt(new \DateTime());
            $taxonomyEntity->setWeight(0);
            $taxonomyEntity->setTaxonomyTypeEntity($taxonomyTypeEntity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($taxonomyEntity);
            $em->flush();


            $currentEntityInfo = Array(
                "taxonomyEntity_Id" => $taxonomyEntity->getId(),
                "taxonomyEntity_word" => $taxonomyEntity->getTaxonomyWord()
            );

            array_push($resultJsonArray,$currentEntityInfo);
        }

        //把json串返回去。
        $json_response = new JsonResponse($resultJsonArray);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;
    }
}
