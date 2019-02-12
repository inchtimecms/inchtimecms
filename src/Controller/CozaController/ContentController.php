<?php

namespace App\Controller\CozaController;

use App\Entity\CommentEntity;
use App\Entity\ContentEntity;
use App\Entity\ContentTypeEntity;
use App\Entity\TaxonomyEntity;
use App\Form\CommentEntityType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 商品类型以外的其他内容类型显示Controller
 */
class ContentController extends BaseController
{
    /**
     * 显示某个内容类型下的所有内容列表
     * id: ContentTypeEntity.id
     * @Route("/content/type/{contentTypeMachineAlias}/contents", name="show_content_type_contents_list")
     */
    public function showContentList(Request $request, ContentTypeEntity $contentTypeEntity, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $query = $em->createQuery('SELECT c FROM App\Entity\ContentEntity c WHERE c.contentTypeEntity = :contentTypeEntity AND c.deleted = 0 ORDER BY c.id DESC');
        $query->setParameter("contentTypeEntity", $contentTypeEntity);

        /**@var ContentEntity[] $pagination**/
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 8);

        return $this->render('themes/cozastore/pages/content_list.html.twig', [
            "paginator" => $pagination,
            "contentTypeEntity" => $contentTypeEntity,
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this
        ]);
    }

    /**
     * 显示单独的内容页面
     *
     * @Route("/content/{id}", name="show_single_content")
     */
    public function showContent(Request $request, ContentEntity $contentEntity, EntityManagerInterface $em)
    {

        $commentForm = $this->createForm(CommentEntityType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            /**@var CommentEntity $commentData**/
            $commentData = $commentForm->getData();

            $commentData->setAuthor($this->getUser());
            $commentData->setCreateAt(new \DateTime());
            $commentData->setContentEntity($contentEntity);

            $em->persist($commentData);
            $em->flush();

            return $this->redirectToRoute("show_single_content",[
                "id" => $contentEntity->getId()
            ]);
        }

        return $this->render('themes/cozastore/pages/content_single.html.twig', [
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "commentForm" => $commentForm->createView(),
            "contentEntity" => $contentEntity,
            "baseController" => $this,
        ]);
    }

    /**
     * 显示某分类词汇下的所有BLOG内容列表
     * @Route("/taxonomy/{id}", name="show_taxonomy_content_list")
     */
    public function showContentListTaxonomy(Request $request, TaxonomyEntity $taxonomyEntity, PaginatorInterface $paginator)
    {
        $fieldTaxonomyTables = $taxonomyEntity->getFieldTaxonomyTableEntitys();

        $contentEntitys = array();
        foreach($fieldTaxonomyTables as $fieldTaxonomyTableEntity){
            $contentEntity = $fieldTaxonomyTableEntity->getContentEntity();
            if ($contentEntity->getDeleted() == 0){
                array_push($contentEntitys, $contentEntity);
            }
        }

        /**@var ContentEntity[] $pagination**/
        $pagination = $paginator->paginate($contentEntitys, $request->query->getInt('page', 1), 15);

        return $this->render('themes/cozastore/pages/taxonomy_content_list.html.twig', [
            "taxonomyEntity" => $taxonomyEntity,
            "paginator" => $pagination,
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this,
        ]);
    }
}
