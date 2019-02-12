<?php

namespace App\Controller\AdminController;

use App\Entity\ProductTypeEntity;
use App\Form\ProductTypeEntityType;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\PayMethodEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use App\Repository\ShipFeeTemplateEntityRepository;
use App\Security\Voter\ProductTypeEntityVoter;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/product/type/entity")
 */
class ProductTypeEntityController extends AbstractController
{
    /**
     * @Route("/list", name="product_type_entity_index", methods="GET")
     */
    public function index(Request $request, ProductTypeEntityRepository $productTypeEntityRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PRODUCT_TYPE_ENTITY_VIEW);

        $productTypeEntitys = $productTypeEntityRepository->findBy(array("deleted" => 0), array("id" => "DESC"));

        $pagination = $paginator->paginate(
            $productTypeEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/product_type_entity/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="product_type_entity_new", methods="GET|POST")
     */
    public function newProductTypeEntity(ContentTypeEntityRepository $contentTypeEntityRepository,
                        ShipFeeTemplateEntityRepository $shipFeeTemplateEntityRepository,
                        PayMethodEntityRepository $payMethodEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PRODUCT_TYPE_ENTITY_NEW);

        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));

        $shipFeeTemplateEntitys = $shipFeeTemplateEntityRepository->findAll();
        $payMethods = $payMethodEntityRepository->findAll();

        return $this->render("admin_pages/product_type_entity/new.html.twig", [
            "contentTypeEntitys" => $contentTypeEntitys,
            "shipFeeTemplateEntitys" => $shipFeeTemplateEntitys,
            "payMethodEntitys" => $payMethods
        ]);
    }

    /**
     * @Route("/new/action", name="product_type_entity_new_action", methods="GET|POST")
     */
    public function newAction(Request $request, ContentTypeEntityRepository $contentTypeEntityRepository,EntityManagerInterface $em,
                              ShipFeeTemplateEntityRepository $shipFeeTemplateEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PRODUCT_TYPE_ENTITY_NEW);

        $csrfToken = $request->request->get("_csrf_token");
        if ($this->isCsrfTokenValid("add_product_type", $csrfToken)) {

            $productTypeEntity = new ProductTypeEntity();
            $productTypeEntity->setProductTypeName($request->request->get("productTypeName"));
            $productTypeEntity->setProductAlias($request->request->get("productTypeAlias"));
            $productTypeEntity->setProductDesc($request->request->get("productTypeDesc"));
            //引用的内容类型
            $contentTypeEntity = $contentTypeEntityRepository->find($request->request->get("refContentType"));
            $productTypeEntity->setContentTypeEntity($contentTypeEntity);
            //引用的内容类型的字段的别名
            $picFields = $request->request->get("refFieldForPic");
            $productTypeEntity->setMainPic($picFields);

            $productTypeEntity->setPriceField($request->request->get("refFieldForPrice"));
            $productTypeEntity->setDiscountPriceField($request->request->get("refFieldForDiscountPrice"));
            $productTypeEntity->setSaleStatus($request->request->get("refFieldForSaleStatus"));
            //销售属性
            //获取表单的所有 input name 键 值 对。
            $requestArray = $request->request->all();
            $salePropArray = array();
            $payMethodArray = array();
            foreach ($requestArray as $requestName => $value) {
                //查找name为 salepropgroup 开头的 请求
                if (stripos($requestName, "propgroup") != FALSE) {
                    array_push($salePropArray, $value);
                }
                //拼接付款方式数组
                if (stripos($requestName, "method") != FALSE) {
                    array_push($payMethodArray, $value);
                }
            }

            $productTypeEntity->setSalePropField($salePropArray);
//            $productTypeEntity->setPayMethods($payMethodArray);

            $shipFeeTemplateEntity = $shipFeeTemplateEntityRepository->find($request->request->get("shipfeetemplate"));
            $productTypeEntity->setShipFeeTemplateEntity($shipFeeTemplateEntity);

            $productTypeEntity->setBoolRealOrVirtual($request->request->get("boolRealOrVirtual"));

            $productTypeEntity->setBoolNeedShip($request->request->get("boolNeedShip"));

            $productTypeEntity->setDeleted(0);

            $em->persist($productTypeEntity);
            $em->flush();

            return $this->redirectToRoute("product_type_entity_index");

        }
    }


    /**
     * @Route("/edit/{id}", name="product_type_entity_edit", methods="GET|POST")
     * @ParamConverter("productTypeEntity", class="App\Entity\ProductTypeEntity", options={"id" = "id"})
     */
    public function edit(Request $request, ProductTypeEntity $productTypeEntity,
                         ContentTypeEntityRepository $contentTypeEntityRepository,
                         ShipFeeTemplateEntityRepository $shipFeeTemplateEntityRepository,
                         PayMethodEntityRepository $payMethodEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PRODUCT_TYPE_ENTITY_EDIT);

        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));
        $shipFeeTemplateEntitys = $shipFeeTemplateEntityRepository->findAll();
        $payMethods = $payMethodEntityRepository->findAll();
        return $this->render("admin_pages/product_type_entity/edit.html.twig", [
            "productTypeEntity" => $productTypeEntity,
            "contentTypeEntitys" => $contentTypeEntitys,
            "shipFeeTemplateEntitys" => $shipFeeTemplateEntitys,
            "payMethodEntitys" => $payMethods
        ]);
    }

    /**
     * @Route("/edit/action/{id}", name="product_type_entity_edit_action", methods="GET|POST")
     * @ParamConverter("productTypeEntity", class="App\Entity\ProductTypeEntity", options={"id" = "id"})
     */
    public function editAction(Request $request, ProductTypeEntity $productTypeEntity,
                               ContentTypeEntityRepository $contentTypeEntityRepository,
                               ShipFeeTemplateEntityRepository $shipFeeTemplateEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(ProductTypeEntityVoter::PRODUCT_TYPE_ENTITY_PRODUCT_TYPE_ALIAS_EDIT, $productTypeEntity);
        $csrfToken = $request->request->get("_csrf_token");
        if ($this->isCsrfTokenValid("edit_product_type", $csrfToken)) {

            $productTypeEntity->setProductTypeName($request->request->get("productTypeName"));
            $productTypeEntity->setProductAlias($request->request->get("productTypeAlias"));
            $productTypeEntity->setProductDesc($request->request->get("productTypeDesc"));
            //引用的内容类型 不可修改
            //$contentTypeEntity = $contentTypeEntityRepository->find($request->request->get("refContentType"));
            //$productTypeEntity->setContentTypeEntity($contentTypeEntity);
            //引用的内容类型的字段的别名
            $picFields = $request->request->get("refFieldForPic");
            $productTypeEntity->setMainPic($picFields);
            $productTypeEntity->setPriceField($request->request->get("refFieldForPrice"));
            $productTypeEntity->setDiscountPriceField($request->request->get("refFieldForDiscountPrice"));
            $productTypeEntity->setSaleStatus($request->request->get("refFieldForSaleStatus"));
            //销售属性
            //获取表单的所有 input name 键 值 对。
            $requestArray = $request->request->all();
            $salePropArray = array();
            $payMethodArray = array();
            foreach ($requestArray as $requestName => $value) {
                //查找name为 salepropgroup 开头的 请求
                if (stripos($requestName, "propgroup") != FALSE) {
                    array_push($salePropArray, $value);
                }
                //拼接付款方式数组
                if (stripos($requestName, "method") != FALSE) {
                    array_push($payMethodArray, $value);
                }
            }
            $productTypeEntity->setSalePropField($salePropArray);
//            $productTypeEntity->setPayMethods($payMethodArray);

            $shipFeeTemplateEntity = $shipFeeTemplateEntityRepository->find($request->request->get("shipfeetemplate"));
            $productTypeEntity->setShipFeeTemplateEntity($shipFeeTemplateEntity);

            $productTypeEntity->setBoolRealOrVirtual($request->request->get("boolRealOrVirtual"));

            $productTypeEntity->setBoolNeedShip($request->request->get("boolNeedShip"));

            $productTypeEntity->setDeleted(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($productTypeEntity);
            $em->flush();

            return $this->redirectToRoute("product_type_entity_index");
        }
    }

    /**
     * 添加商品之前，渲染商品类型页面
     * @Route("/product/type/list", name="product_type_list", methods="GET|POST")
     */
    public function renderProductTypeListPage(ProductTypeEntityRepository $productTypeEntityRepository): Response
    {
        $productTypeEntitys = $productTypeEntityRepository->findBy(array("deleted" => 0), array("id" => "DESC"));

        return $this->render("admin_pages/product_type_entity/ProductTypeList.html.twig", [
            "productTypeEntitys" => $productTypeEntitys
        ]);
    }

    /**
     * 显示所有商品页面
     * @Route("/product/content/list", name="product_content_list", methods="GET|POST")
     */
    public function renderProductContentListPage(Request $request, ProductTypeEntityRepository $productTypeEntityRepository, PaginatorInterface $paginator): Response
    {
        //获取所有delete ＝ 0的商品类型
        $productTypeEntitys = $productTypeEntityRepository->findBy(array("deleted" => 0), array("id" => "DESC"));
        //获取所有商品类型对应的内容类型
        $contentTypeEntityArray = array();
        foreach ($productTypeEntitys as $productTypeEntity) {
            $contentTypeEntity = $productTypeEntity->getContentTypeEntity();
            array_push($contentTypeEntityArray, $contentTypeEntity);
        }

        //DQL查询
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT c FROM App\Entity\ContentEntity c WHERE c.contentTypeEntity IN (:contentTypeArray) AND c.deleted = 0 ORDER BY c.id DESC');
        $query->setParameter('contentTypeArray', $contentTypeEntityArray);
        $contents = $query->getResult(); //获取内容

        $pagination = $paginator->paginate(
            $contents, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/product_type_entity/product_list.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("/delete/{id}", name="product_type_entity_delete", methods="GET|POST")
     * @ParamConverter("productTypeEntity", class="App\Entity\ProductTypeEntity", options={"id" = "id"})
     */
    public function delete(ProductTypeEntity $productTypeEntity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::PRODUCT_TYPE_ENTITY_DELETE);
        //删除商品类型,对应的商品内容也要删除。
        $contentTypeEntity = $productTypeEntity->getContentTypeEntity();
        $contentEntitys = $contentTypeEntity->getContentEntitys();
        foreach ($contentEntitys as $contentEntity) {
            $contentEntity->setDeleted(1);
            $em->persist($contentEntity);
        }

        $em->remove($productTypeEntity);
        $em->flush();

        return $this->redirectToRoute("product_type_entity_index");
    }


}
