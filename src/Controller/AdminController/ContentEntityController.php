<?php

namespace App\Controller\AdminController;

use App\Entity\FieldContentTableEntity;
use App\Entity\FieldFileTableEntity;
use App\Entity\FieldImageTableEntity;
use App\Entity\FieldProductPropsTableEntity;
use App\Entity\FieldTableEntity;
use App\Entity\FieldTaxonomyTableEntity;
use App\Entity\FieldTextTableEntity;
use App\Repository\FileManagerEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use App\Repository\TaxonomyEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use App\Utils\YamlReader;
use App\Entity\ContentEntity;
use App\Entity\ContentTypeEntity;
use App\Repository\ContentEntityRepository;
use App\Repository\ContentTypeEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/content/entity")
 */
class ContentEntityController extends AbstractController
{
    /**
     * @Route("/list", name="content_entity_index", methods="GET")
     */
    public function index(Request $request, ContentEntityRepository $contentEntityRepository,
                          PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_ENTITY_VIEW);

        $result = $contentEntityRepository->findBy(array("deleted" => 0), array("id" => "DESC"));
        $pagination = $paginator->paginate(
            $result, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/content_entity/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * 转到选择内容类型的页面，选择完内容类型后，转到添加内容页面。
     * @Route("/new_page", name="content_entity_new", methods="GET")
     */
    public function addContent(ContentTypeEntityRepository $contentTypeEntityRepository): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_ENTITY_NEW);

        //获取所有的内容类型
        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));
        return $this->render('admin_pages/content_entity/contentTypeList.html.twig', [
            "contentTypeEntitys" => $contentTypeEntitys
        ]);
    }

    /**
     * 添加内容页面,此页获取当前内容类型所有的字段，并在content_entity/new.html.twig页面显示 form。
     * @Route("/new/{contentTypeEntity_id}", name="content_entity_new_page", methods="GET")
     * @ParamConverter("contentTypeEntity", class="App\Entity\ContentTypeEntity", options={"id" = "contentTypeEntity_id"})
     */
    public function addNewContentPage(Request $request, ContentTypeEntity $contentTypeEntity): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_ENTITY_NEW);

        //获取当前内容类型的所有字段
        $fieldTypeEntitys = $contentTypeEntity->getFieldsTypeEntitys();

        $projectRoot = $this->getParameter("root_directory");
        $yamlPath = $projectRoot . "/config/filemime.yaml";

        $value = YamlReader::getFileMime($yamlPath);

        return $this->render('admin_pages/content_entity/new.html.twig', [
            "fieldTypeEntitys" => $fieldTypeEntitys,
            "value" => $value,
            "contentTypeEntity" => $contentTypeEntity
        ]);
    }

    /**
     * 添加内容表单处理
     * @Route("/new_content_action/{contentTypeEntity_id}", name="content_entity_new_action", methods="POST")
     * @ParamConverter("contentTypeEntity", class="App\Entity\ContentTypeEntity", options={"id" = "contentTypeEntity_id"})
     */
    public function addContentAction(ContentTypeEntity $contentTypeEntity, Request $request, EntityManagerInterface $em,
                                     ContentEntityRepository $contentEntityRepository,
                                     FileManagerEntityRepository $fileManagedRepo,
                                     TaxonomyEntityRepository $taxonomyEntityRepository): Response
    {
        $token = $request->request->get("_csrf_token");
        if ($this->isCsrfTokenValid("add_content_entity", $token)) {

            //获取表单的所有 input name 键 值 对。
            $requestArray = $request->request->all();

            $em->getConnection()->beginTransaction();

            try {
                $contentEntity = new ContentEntity();
                $contentEntity->setTitle($requestArray["contentTitle"]);
                $contentEntity->setBody($requestArray["contentBody"]);
                $contentEntity->setContentTypeEntity($contentTypeEntity);
                $user = $this->getUser();
                $contentEntity->setAuthor($user);
                $contentEntity->setDeleted(0);
                $contentEntity->setCreateAt(new \DateTime());
                $contentEntity->setChangeAt(new \DateTime());

                $this->denyAccessUnlessGranted("contentEntity[contentTypeAlias][new]", $contentEntity);

                $em->persist($contentEntity);

                //请求参数name格式： fieldTypeValueInSQL : fieldTypeEntityAlias : fileManagedId : 最后的为上传图片的 imgAlt/imgTitle
                foreach ($requestArray as $requestName => $value) {
                    //排除标题，BODY，其他的为字段, 并且其他字段的 Input不能为空
                    if ($requestName !== "contentTitle" && $requestName !== "contentBody"
                        && $requestName !== "group1" && $requestName !== "group2" && $requestName !== "sale-prop") {

                        $fieldInfoArray = explode(":", $requestName);
                        //根据请求Name的长度，处理不同的存储
                        switch (sizeof($fieldInfoArray)) {

                            case 2:
                                //普通的input字段
                                if ($value !== "") {
                                    //$taxonomyEntitysArray = explode(",",$fieldInfoArray[1]);

                                    //长文本 text 类型的字段
                                    if ($fieldInfoArray[0] == "text") {
                                        $fieldTextTableEntity = new FieldTextTableEntity();
                                        $fieldTextTableEntity->setDeleted(0);
                                        $fieldTextTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldTextTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                        $fieldTextTableEntity->setContentEntity($contentEntity);
                                        $fieldTextTableEntity->setContentTypeEntity($contentTypeEntity);
                                        $fieldTextTableEntity->setFieldTableValue($value);

                                        $em->persist($fieldTextTableEntity);

                                    } elseif ($fieldInfoArray[0] == "ref_taxonomyType") {

                                        $taxonomyEntitysArray = explode(",", $value);

                                        foreach ($taxonomyEntitysArray as $item) {

                                            $fieldTaxonomyTableEntity = new FieldTaxonomyTableEntity();
                                            $fieldTaxonomyTableEntity->setContentEntity($contentEntity);
                                            $fieldTaxonomyTableEntity->setFieldAlias($fieldInfoArray[1]);
                                            $fieldTaxonomyTableEntity->setCreateAt(new \DateTime());

                                            $currTaxonomyEntity = $taxonomyEntityRepository->find($item);

                                            $fieldTaxonomyTableEntity->setTaxonomyEntity($currTaxonomyEntity);
                                            $em->persist($fieldTaxonomyTableEntity);
                                        }
                                    } elseif ($fieldInfoArray[0] == "ref_contentType") {

                                        $contentEntitysArray = explode(",", $value);
                                        foreach ($contentEntitysArray as $item) {

                                            $fieldContentTableEntity = new FieldContentTableEntity();
                                            $fieldContentTableEntity->setContentEntity($contentEntity);
                                            $fieldContentTableEntity->setFieldAlias($fieldInfoArray[1]);
                                            $fieldContentTableEntity->setCreateAt(new \DateTime());

                                            $currContentEntity = $contentEntityRepository->find($item);
                                            $fieldContentTableEntity->setRefContentEntity($currContentEntity);

                                            $em->persist($fieldContentTableEntity);
                                        }
                                    } else {
                                        //普通string类型的字段
                                        $fieldTableEntity = new FieldTableEntity();

                                        $fieldTableEntity->setContentTypeEntity($contentTypeEntity);
                                        $fieldTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldTableEntity->setContentEntity($contentEntity);
                                        $fieldTableEntity->setFieldTableValue($value);
                                        $fieldTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                        $fieldTableEntity->setDeleted(0);

                                        $em->persist($fieldTableEntity);
                                    }

                                }

                                break;

                            case 3:
                                //图片上传Input

                                //已上传的文件的id
                                $uploadedFileId = $fieldInfoArray[2];
                                $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);

                                $fieldFileTableEntity = new FieldFileTableEntity();
                                $fieldFileTableEntity->setContentEntity($contentEntity);
                                $fieldFileTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                $fieldFileTableEntity->setContentTypeEntity($contentTypeEntity);
                                $fieldFileTableEntity->setFileManagedEntity($uploadFileEntity);
                                $fieldFileTableEntity->setDeleted(0);

                                $em->persist($fieldFileTableEntity);

                                break;
                            case 4:
                                //图片上传input

                                //上传的文件的id
                                $uploadedFileId = $fieldInfoArray[2];
                                $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);
                                //获取文件的存放路径 获取图片的长宽信息
                                $imageFullPath = $this->getParameter("root_directory") . "/public" . $uploadFileEntity->getUri();
                                list($width, $height) = getimagesize($imageFullPath);

                                $fieldImageTableEntity = new FieldImageTableEntity();

                                $fieldImageTableEntity->setContentEntity($contentEntity);
                                $fieldImageTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                $fieldImageTableEntity->setContentTypeEntity($contentTypeEntity);
                                $fieldImageTableEntity->setFileManagedEntity($uploadFileEntity);
                                $fieldImageTableEntity->setImageTitle($value);
                                $fieldImageTableEntity->setImageAlt($value);
                                $fieldImageTableEntity->setImageWidth($width);
                                $fieldImageTableEntity->setImageHeight($height);
                                $fieldImageTableEntity->setDeleted(0);

                                $em->persist($fieldImageTableEntity);

                                break;

                        }

                    }
                }

                //商品属性内容
                if ($request->request->get("group1") !== null || $request->request->get("group2") !== null || $request->request->get("sale-prop") !== null) {
                    $productPropsTable = new FieldProductPropsTableEntity();
                    $productPropsTable->setContentEntity($contentEntity);
                    $productPropsTable->setGroup1PropsJson($request->request->get("group1"));
                    $productPropsTable->setGroup2PropsJson($request->request->get("group2"));
                    $productPropsTable->setFieldProductPropsValue($request->request->get("sale-prop"));
                    $productPropsTable->setDeleted(false);
                    $em->persist($productPropsTable);
                }

                $em->flush();
                $em->getConnection()->commit();

            } catch (\Exception $e) {
                $em->getConnection()->rollBack();
                return $this->render(":bundles/TwigBundle/Exception:error500.html.twig", [
                    "exception" => $e->getMessage()
                ]);
            }

            return $this->redirectToRoute("content_entity_index");
        } else {
            return $this->render(":bundles/TwigBundle/Exception:error500.html.twig", [
                "exception" => "页面令牌出错，请刷新网页后重试！",
            ]);
        }
    }

    /**
     * 删除contentEntity ,修改表列deleted值为1, 及该内容类型对应的字段表的行的deleted值为1
     * @Route("/content_delete/{contentEntity_id}",name="content_entity_delete",methods="GET|POST")
     * @ParamConverter("contentEntity", class="App\Entity\ContentEntity", options={"id" = "contentEntity_id"})
     */
    public function deleteContentAction(ContentEntity $contentEntity, EntityManagerInterface $em): Response
    {

        $this->denyAccessUnlessGranted("contentEntity[contentTypeAlias][delete]", $contentEntity);

        //设置当前行的deleted为1
        $contentEntity->setDeleted(1);

        //获取当前内容类型的所有字段
        $contentFields = $contentEntity->getFieldTableEntitys();
        foreach ($contentFields as $contentField) {
            $contentField->setDeleted(1);
            $em->persist($contentField);
        }

        $contentTextFields = $contentEntity->getFieldTextTableEntitys();
        foreach ($contentTextFields as $contentTextField) {
            $contentTextField->setDeleted(1);
            $em->persist($contentTextField);
        }

        $contentFileFields = $contentEntity->getFieldFileTableEntitys();
        foreach ($contentFileFields as $contentFileField) {
            $contentFileField->setDeleted(1);
            $em->persist($contentFileField);
        }


        $contentImageFields = $contentEntity->getFieldImageTableEntitys();
        foreach ($contentImageFields as $contentImageField) {
            $contentImageField->setDeleted(1);
            $em->persist($contentImageField);
        }

        $em->persist($contentEntity);
        $em->flush();
        if ($contentEntity->getContentTypeEntity()->getProductTypeEntity() != null) {
            return $this->redirectToRoute("product_content_list");
        }
        return $this->redirectToRoute("content_entity_index");
    }


    /**
     * 编辑contentEntity
     * @Route("/content_edit/{contentEntity_id}",name="content_entity_edit",methods="GET|POST")
     * @ParamConverter("contentEntity", class="App\Entity\ContentEntity", options={"id" = "contentEntity_id"})
     */
    public function editContentPage(Request $request, ContentEntity $contentEntity): Response
    {
        $this->denyAccessUnlessGranted("contentEntity[contentTypeAlias][edit]", $contentEntity);

        //获取当前内容类型的所有字段
        $contentTypeEntity = $contentEntity->getContentTypeEntity();
        $fieldTypeEntitys = $contentTypeEntity->getFieldsTypeEntitys();

        $projectRoot = $this->getParameter("root_directory");
        $yamlPath = $projectRoot . "/config/filemime.yaml";
        $value = YamlReader::getFileMime($yamlPath);
        return $this->render("admin_pages/content_entity/edit.html.twig", [
            "contentEntity" => $contentEntity,
            "contentTypeEntity" => $contentTypeEntity,
            "fieldTypeEntitys" => $fieldTypeEntitys,
            "value" => $value
        ]);
    }

    /**
     * @Route("/edit_content_action/{contentEntity_id}", name="content_entity_edit_action", methods = "POST")
     * @ParamConverter("contentEntity", class="App\Entity\ContentEntity", options={"id" = "contentEntity_id"})
     */
    public function editContentAction(ContentEntity $contentEntity, Request $request, FileManagerEntityRepository $fileManagedRepo, EntityManagerInterface $em,
                                      ContentEntityRepository $contentEntityRepository, TaxonomyEntityRepository $taxonomyEntityRepository): Response
    {
        $this->denyAccessUnlessGranted("contentEntity[contentTypeAlias][edit]", $contentEntity);

        $token = $request->request->get("_csrf_token");
        if (!$this->isCsrfTokenValid("edit_content_entity", $token)) {
            return $this->render("pages/error500.html.twig", [
                "exception" => "页面令牌出错，请刷新网页后重试！",
            ]);
        }
        //获取表单的所有 input name 键 值 对。
        $requestArray = $request->request->all();
        $em->getConnection()->beginTransaction();

        try {

            $contentEntity->setTitle($requestArray["contentTitle"]);
            $contentEntity->setBody($requestArray["contentBody"]);
            $contentEntity->setDeleted(0);
            $contentEntity->setChangeAt(new \DateTime());
            $em->persist($contentEntity);

            //请求参数name格式： fieldTypeValueInSQL : fieldTypeEntityAlias : fileManagedId : 最后的为上传图片的 imgAlt/imgTitle
            foreach ($requestArray as $requestName => $value) {
                //排除标题，BODY，其他的为字段, 并且其他字段的 Input不能为空
                if ($requestName !== "contentTitle" && $requestName !== "contentBody"
                    && $requestName !== "group1" && $requestName !== "group2" && $requestName !== "sale-prop") {

                    $fieldInfoArray = explode(":", $requestName);
                    //根据请求Name的长度，处理不同的存储
                    switch (sizeof($fieldInfoArray)) {

                        case 2:
                            //普通的input字段
                            if ($value !== "") {

                                //长文本 text 类型的字段
                                if ($fieldInfoArray[0] == "text") {
                                    $fieldTextTableEntitys = $contentEntity->getFieldTextTableEntitys();
                                    if (sizeof($fieldTextTableEntitys) == 0) {
                                        $fieldTextTableEntity = new FieldTextTableEntity();
                                        $fieldTextTableEntity->setDeleted(0);
                                        $fieldTextTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldTextTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                        $fieldTextTableEntity->setContentEntity($contentEntity);
                                        $fieldTextTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                        $fieldTextTableEntity->setFieldTableValue($value);

                                        $em->persist($fieldTextTableEntity);

                                    } else {
                                        foreach ($fieldTextTableEntitys as $fieldTextTableEntity) {
                                            //如果当前fieldTextTableEntity的机器别名 与 Input传过来的别名一致
                                            if ($fieldTextTableEntity->getFieldAliasInContentTypeEntity() == $fieldInfoArray[1]) {
                                                $fieldTextTableEntity->setDeleted(0);
                                                $fieldTextTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                                $fieldTextTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                                $fieldTextTableEntity->setContentEntity($contentEntity);
                                                $fieldTextTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                                $fieldTextTableEntity->setFieldTableValue($value);

                                                $em->persist($fieldTextTableEntity);
                                            }
                                        }
                                    }

                                } elseif ($fieldInfoArray[0] == "ref_taxonomyType") {

                                    $taxonomyEntitysArray = explode(",", $value);

                                    //获取已存在的$taxonomyTableEntity,删除已存在的,再重新添加新的
                                    $taxonomyTableEntitys = $contentEntity->getFieldTaxonomyTableEntitys();

                                    foreach ($taxonomyTableEntitys as $taxonomyTableEntity) {
                                        $em->remove($taxonomyTableEntity);
                                    }

                                    foreach ($taxonomyEntitysArray as $item) {

                                        $fieldTaxonomyTableEntity = new FieldTaxonomyTableEntity();
                                        $fieldTaxonomyTableEntity->setContentEntity($contentEntity);
                                        $fieldTaxonomyTableEntity->setFieldAlias($fieldInfoArray[1]);
                                        $fieldTaxonomyTableEntity->setCreateAt(new \DateTime());

                                        $currTaxonomyEntity = $taxonomyEntityRepository->find($item);

                                        $fieldTaxonomyTableEntity->setTaxonomyEntity($currTaxonomyEntity);

                                        $em->persist($fieldTaxonomyTableEntity);
                                    }

                                } elseif ($fieldInfoArray[0] == "ref_contentType") {

                                    $contentEntitysArray = explode(",", $value);

                                    //获取已存在的$fieldContentTableEntitys
                                    $fieldContentTableEntitys = $contentEntity->getFieldContentTableEntitys();
                                    foreach ($fieldContentTableEntitys as $fieldContentTableEntity) {
                                        $em->remove($fieldContentTableEntity);
                                    }

                                    foreach ($contentEntitysArray as $item) {

                                        $fieldContentTableEntity = new FieldContentTableEntity();
                                        $fieldContentTableEntity->setContentEntity($contentEntity);
                                        $fieldContentTableEntity->setFieldAlias($fieldInfoArray[1]);
                                        $fieldContentTableEntity->setCreateAt(new \DateTime());

                                        $currContentEntity = $contentEntityRepository->find($item);
                                        $fieldContentTableEntity->setRefContentEntity($currContentEntity);

                                        $em->persist($fieldContentTableEntity);
                                    }

                                } else {
                                    //普通string类型的字段
                                    $fieldTableEntitys = $contentEntity->getFieldTableEntitys();
                                    if (sizeof($fieldTableEntitys) == 0) {
                                        //普通string类型的字段
                                        $fieldTableEntity = new FieldTableEntity();

                                        $fieldTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                        $fieldTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldTableEntity->setContentEntity($contentEntity);
                                        $fieldTableEntity->setFieldTableValue($value);
                                        $fieldTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                        $fieldTableEntity->setDeleted(0);

                                        $em->persist($fieldTableEntity);

                                    } else {
                                        foreach ($fieldTableEntitys as $fieldTableEntity) {
                                            if ($fieldTableEntity->getFieldAliasInContentTypeEntity() == $fieldInfoArray[1]) {
                                                $fieldTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                                $fieldTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                                $fieldTableEntity->setContentEntity($contentEntity);
                                                $fieldTableEntity->setFieldTableValue($value);
                                                $fieldTableEntity->setFieldTableValueType($fieldInfoArray[0]);
                                                $fieldTableEntity->setDeleted(0);

                                                $em->persist($fieldTableEntity);
                                            }

                                        }
                                    }

                                }

                            }

                            break;

                        case 3:
                            //文件上传Input
                            $fieldFileTableEntitys = $contentEntity->getFieldFileTableEntitys();

                            //如果原来内容中没有文件字段，创建对象
                            if (sizeof($fieldFileTableEntitys) == 0) {
                                //已上传的文件的id
                                $uploadedFileId = $fieldInfoArray[2];
                                $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);

                                $fieldFileTableEntity = new FieldFileTableEntity();
                                $fieldFileTableEntity->setContentEntity($contentEntity);
                                $fieldFileTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                $fieldFileTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                $fieldFileTableEntity->setFileManagedEntity($uploadFileEntity);
                                $fieldFileTableEntity->setDeleted(0);

                                $em->persist($fieldFileTableEntity);

                            } else {
                                foreach ($fieldFileTableEntitys as $fieldFileTableEntity) {
                                    if ($fieldFileTableEntity->getFieldAliasInContentTypeEntity() == $fieldInfoArray[1]) {
                                        $fieldFileTableEntity->setContentEntity($contentEntity);
                                        $fieldFileTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldFileTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                        $fieldFileTableEntity->setDeleted(0);
                                        //已上传的文件的id
                                        $uploadedFileId = $fieldInfoArray[2];
                                        $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);
                                        $fieldFileTableEntity->setFileManagedEntity($uploadFileEntity);


                                        $em->persist($fieldFileTableEntity);
                                    }
                                }
                            }

                            break;
                        case 4:
                            //图片上传input
                            $fieldImageTableEntitys = $contentEntity->getFieldImageTableEntitys();

                            //如果ContentEntity中没有image值 创建一个对象
                            if (sizeof($fieldImageTableEntitys) == 0) {
                                //上传的文件的id
                                $uploadedFileId = $fieldInfoArray[2];
                                $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);
                                //获取文件的存放路径 获取图片的长宽信息
                                $imageFullPath = $this->getParameter("root_directory") . "/public" . $uploadFileEntity->getUri();
                                list($width, $height) = getimagesize($imageFullPath);

                                $fieldImageTableEntity = new FieldImageTableEntity();

                                $fieldImageTableEntity->setContentEntity($contentEntity);
                                $fieldImageTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                $fieldImageTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                $fieldImageTableEntity->setFileManagedEntity($uploadFileEntity);
                                $fieldImageTableEntity->setImageTitle($value);
                                $fieldImageTableEntity->setImageAlt($value);
                                $fieldImageTableEntity->setImageWidth($width);
                                $fieldImageTableEntity->setImageHeight($height);
                                $fieldImageTableEntity->setDeleted(0);

                                $em->persist($fieldImageTableEntity);

                            } else {
                                foreach ($fieldImageTableEntitys as $fieldImageTableEntity) {
                                    if ($fieldImageTableEntity->getFieldAliasInContentTypeEntity() == $fieldInfoArray[1]) {
                                        //上传的文件的id
                                        $uploadedFileId = $fieldInfoArray[2];
                                        $uploadFileEntity = $fileManagedRepo->find($uploadedFileId);
                                        //获取文件的存放路径 获取图片的长宽信息
                                        $imageFullPath = $this->getParameter("root_directory") . "/public" . $uploadFileEntity->getUri();
                                        list($width, $height) = getimagesize($imageFullPath);

                                        $fieldImageTableEntity->setContentEntity($contentEntity);
                                        $fieldImageTableEntity->setFieldAliasInContentTypeEntity($fieldInfoArray[1]);
                                        $fieldImageTableEntity->setContentTypeEntity($contentEntity->getContentTypeEntity());
                                        $fieldImageTableEntity->setFileManagedEntity($uploadFileEntity);
                                        $fieldImageTableEntity->setImageTitle($value);
                                        $fieldImageTableEntity->setImageAlt($value);
                                        $fieldImageTableEntity->setImageWidth($width);
                                        $fieldImageTableEntity->setImageHeight($height);
                                        $fieldImageTableEntity->setDeleted(0);

                                        $em->persist($fieldImageTableEntity);

                                    }
                                }
                            }

                            break;
                    }

                }

            }

            //商品属性内容
            if ($request->request->get("group1") !== null || $request->request->get("group2") !== null || $request->request->get("sale-prop") !== null) {
                $productPropsTable = $contentEntity->getFieldProductPropsTableEntity();
                $productPropsTable->setContentEntity($contentEntity);
                $productPropsTable->setGroup1PropsJson($request->request->get("group1"));
                $productPropsTable->setGroup2PropsJson($request->request->get("group2"));
                $productPropsTable->setFieldProductPropsValue($request->request->get("sale-prop"));
                $productPropsTable->setDeleted(false);
                $em->persist($productPropsTable);
            }

            $em->flush();
            $em->getConnection()->commit();

        } catch (\Exception $e) {
            $em->getConnection()->rollBack();

            return $this->render(":bundles/TwigBundle/Exception:error500.html.twig", [
                "exception" => $e->getMessage()
            ]);
        }

        return $this->redirectToRoute("content_entity_index");
    }

    /**
     * 添加内容时，当input 为引用 内容 时，获取相关contentEntity, 返回内容标题和id JSON串
     * @Route("/fetch_content_action", name="fetch_content_action",methods="POST")
     */
    public function fetchContentAction(Request $request, EntityManagerInterface $em): Response
    {
        //获取当前字段引用的内容类型
        $contentTypeIds = $request->request->get("field_ref_contentTypeIds");
        $contentTypeIdArray = explode(",", $contentTypeIds);
        //获取当前输入的关键字
        $keywords = $request->request->get("field_ref_contentKeyWords");

        $query_dql = 'SELECT c FROM App\Entity\ContentEntity c WHERE c.title LIKE :keywords AND c.contentTypeEntity IN (:contentTypeIds) AND c.deleted = 0';
        $query = $em->createQuery($query_dql);
        $query->setParameter('keywords', '%' . $keywords . '%');
        $query->setParameter('contentTypeIds', $contentTypeIdArray);
        $contentEntitys = $query->getResult();

        $contentEntityResults = Array();
        foreach ($contentEntitys as $contentEntity) {
            $array = array(
                "content_id" => $contentEntity->getId(),
                "content_title" => $contentEntity->getTitle()
            );
            array_push($contentEntityResults, $array);
        }

        //把$fileManagedEntity以json串返回去。
        $json_response = new JsonResponse($contentEntityResults);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;

    }
}
