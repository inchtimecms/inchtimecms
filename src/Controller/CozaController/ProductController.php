<?php

namespace App\Controller\CozaController;

use App\Entity\CommentEntity;
use App\Entity\ContentEntity;
use App\Entity\ProductTypeEntity;
use App\Entity\TaxonomyEntity;
use App\Form\CommentEntityType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 商品类型以外的其他内容类型显示Controller
 */
class ProductController extends BaseController
{
    /**
     * 显示某分类词汇下的所有商品内容列表
     * @Route("/taxonomy/product/{id}", name="show_taxonomy_product_list")
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

        return $this->render('themes/cozastore/pages/taxonomy_product_list.html.twig', [
            "taxonomyEntity" => $taxonomyEntity,
            "paginator" => $pagination,
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this,
        ]);
    }

    /**
     * 显示商品单页
     * @Route("/product/{id}", name="show_product_detail")
     */
    public function showProductDetail(Request $request, ContentEntity $productEntity, EntityManagerInterface $em)
    {
        $commentForm = $this->createForm(CommentEntityType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            /**@var CommentEntity $commentData**/
            $commentData = $commentForm->getData();

            $commentData->setAuthor($this->getUser());
            $commentData->setCreateAt(new \DateTime());
            $commentData->setContentEntity($productEntity);

            $em->persist($commentData);
            $em->flush();

            return $this->redirectToRoute("show_product_detail",[
                "id" => $productEntity->getId()
            ]);
        }

        return $this->render('themes/cozastore/pages/product_detail.html.twig', [
            "productEntity" => $productEntity,
            "commentForm" => $commentForm->createView(),
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this,
        ]);
    }

    /**
     * 显示某个商品类型下的所有商品
     * @Route("/product/type/{id}", name="show_product_type_products_list")
     */
    public function showProductTypeList(ProductTypeEntity $productTypeEntity)
    {
        $productContentEntities = $productTypeEntity->getContentTypeEntity()->getContentEntitys();

        return $this->render('themes/cozastore/pages/product_list.html.twig', [
            "productTypeEntity" => $productTypeEntity,
            "productContentEntities" => $productContentEntities,
            "system" => $this->getSystemEntity(),
            "mainMenu" => $this->getMainMenuEntity(),
            "baseController" => $this,
        ]);
    }
}
