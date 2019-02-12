<?php

namespace App\Controller\AdminController;

use App\Entity\ContentTypeEntity;
use App\Form\ContentTypeEntityType;
use App\Repository\ContentTypeEntityRepository;
use App\Security\Voter\ContentTypeEntityVoter;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/content/type/entity")
 */
class ContentTypeEntityController extends AbstractController
{
    /**
     * @Route("/list", name="content_type_entity_index", methods="GET")
     */
    public function index(Request $request, ContentTypeEntityRepository $contentTypeEntityRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_TYPE_ENTITY_VIEW);

        $contentTypeEntitys =  $contentTypeEntityRepository->findAll();
        $pagination = $paginator->paginate(
            $contentTypeEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('admin_pages/content_type_entity/index.html.twig',[
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="content_type_entity_new", methods="GET|POST")
     */
    public function addContentTypeEntity(): Response
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_TYPE_ENTITY_NEW);

        return $this->render('admin_pages/content_type_entity/new.html.twig');
    }

    /**
     * 添加、编辑内容类型的表单ACTION
     * @Route("/add", name="content_type_entity_add", methods="GET|POST")
     * @Route("/edit", name="content_type_entity_edit", methods="GET|POST")
     */
    public function addContentType(Request $request, EntityManagerInterface $em): Response
    {

        $this->denyAccessUnlessGranted(UserPermissionVoter::CONTENT_TYPE_ENTITY_NEW);

        $contentTypeName = $request->request->get("newContentTypeName");
        $contentTypeAlias = $request->request->get("newContentTypeMachineAlias");
        $contentTypeDesc = $request->request->get("newContentTypeDescription");

        if ($contentTypeName !=="" && $contentTypeAlias !== ""){
            $contentTypeEntity = new ContentTypeEntity();

            $contentTypeEntity->setContentTypeName($contentTypeName);
            $contentTypeEntity->setContentTypeMachineAlias($contentTypeAlias);
            $contentTypeEntity->setContentTypeDescription($contentTypeDesc);
            $contentTypeEntity->setDeleted(0);

            $em->persist($contentTypeEntity);
            $em->flush();

            //获取当前的route，如果是add转到添加字段的页面，如果是edit则转到首页
            $routeName = $request->attributes->get('_route');
            if($routeName == "content_type_entity_add"){
                //内容类型添加到数据库后，转到添加字段页面
                return $this->redirectToRoute("content_type_field_add",[
                    "contentType" => $contentTypeAlias,
                ]);
            }
        }

        return $this->redirectToRoute("content_type_entity_index");
    }

    /**
     * 添加字段的页面，route参数{contentType}为给对应的文章类型添加字段
     * @Route("/{contentType}/add_field", name="content_type_field_add", methods="GET|POST")
     */
    public function addContentTypeField(string $contentType): Response
    {
        //把当前的内容类型，传递到添加字段页面
        return $this->render('admin_pages/field_type_entity/new.html.twig',[
            "contentType" => $contentType
        ]);
    }

    /**
     * 编辑字段的页面，route参数{contentType}为给对应的文章类型下的所有字段
     * @Route("/edit_field/{id}", name="content_type_entity_fields_edit", methods="GET|POST")
     */
    public function editContentTypeField(int $id): Response
    {
        return $this->redirectToRoute("field_type_entity_index",[
            "id" => $id
        ]);

    }

    /**
     * 编辑内容类型,根据id获取当前的contentTypeEntity 然后做回显 并编辑
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="content_type_entity_edit", methods="GET|POST")
     * @Route("/{id}/edit/post", requirements={"id": "\d+"}, name="content_type_entity_edit_post", methods="GET|POST")
     */
    public function editContentType(int $id, ContentTypeEntityRepository $contentTypeEntityRepository, EntityManagerInterface $em, Request $request):Response
    {
        $contentTypeEntity = $contentTypeEntityRepository->findOneBy(array('id' => $id));

        $this->denyAccessUnlessGranted(ContentTypeEntityVoter::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_EDIT, $contentTypeEntity);
        //如果是 routename = content_type_entity_edit_post 代表编辑好了内容，传参数过来，保存到库
        $routeName = $request->attributes->get('_route');
        if($routeName == "content_type_entity_edit_post"){
            $contentTypeName = $request->request->get("newContentTypeName");
            $contentTypeAlias = $request->request->get("newContentTypeMechineAlias");
            $contentTypeDesc = $request->request->get("newContentTypeDescription");

            if ($contentTypeName !=="" && $contentTypeAlias != "") {

                $contentTypeEntity->setContentTypeName($contentTypeName);
                $contentTypeEntity->setContentTypeMachineAlias($contentTypeAlias);
                $contentTypeEntity->setContentTypeDescription($contentTypeDesc);
                $contentTypeEntity->setDeleted(0);

                $em->persist($contentTypeEntity);
                $em->flush();
            }
            return $this->redirectToRoute("content_type_entity_index");
        }


        return $this->render('admin_pages/content_type_entity/edit.html.twig',[
            "contentTypeEntity" => $contentTypeEntity
        ]);
    }

    /**
     * 删除当前内容类型,根据id获取当前的contentTypeEntity然后删除，删除内容类型也要删除对应的conentEntity
     * @Route("/{id}/delete", requirements={"id": "\d+"}, name="content_type_entity_delete", methods="GET|POST")
     */
    public function deleteContentType(int $id, EntityManagerInterface $em, ContentTypeEntityRepository $contentTypeEntityRepository):Response
    {
        $contentTypeEntity = $contentTypeEntityRepository->findOneBy(array('id' => $id));

        $this->denyAccessUnlessGranted(ContentTypeEntityVoter::CONTENT_TYPE_ENTITY_CONTENT_TYPE_ALIAS_DELETE, $contentTypeEntity);
        //删除内容类型时，把内容类型 delete 字段设置为1
        $contentTypeEntity->setDeleted(1);

        $em->persist($contentTypeEntity);
        $em->flush();

        return $this->redirectToRoute("content_type_entity_index");
    }
    /**
     * 判断当前新填写的内容分类的机器别名是否已存在.此方法用于Ajax
     * 返回值：如果不存在则返回"0"，如果存在返回"1"。
     *
     * @Route("/alias_unique", name="content_type_entity_alias_unique", methods="GET|POST")
     */
    public function aliasUnique(Request $request, ContentTypeEntityRepository $contentTypeEntityRepository): Response
    {
        $editedAlias =
            $request->getMethod() == "GET" ? $request->query->get("edited_alias") : $request->request->get("edited_alias");

        $contentTypeEntity = $contentTypeEntityRepository->findOneBy(array('contentTypeMachineAlias' => $editedAlias));

        return new Response($contentTypeEntity == null ? "0" : "1");
    }


    /**
     * 输出json格式，所有的内容类型，用于内容类型添加 内容 类型的字段时ajax插入DOM元素
     * @Route("/getAll_json", name="content_type_entity_getAllJson", methods="GET|POST")
     */
    public function getAllJson(ContentTypeEntityRepository $contentTypeEntityRepository):Response
    {
        $contentTypeEntitys = $contentTypeEntityRepository->findAll();

        $ajaxContentTypeResults = Array();
        foreach ($contentTypeEntitys as $contentTypeEntity)
        {
            $ajaxContentTypeResult = array(
                "id" => $contentTypeEntity->getId(),
                "typeName" => $contentTypeEntity->getContentTypeName(),
                "typeDesc" => $contentTypeEntity->getContentTypeDescription()
            );

            array_push($ajaxContentTypeResults, $ajaxContentTypeResult);
        }

        //把$fileManagedEntity以json串返回去。
        $json_response = new JsonResponse($ajaxContentTypeResults);
        $json_response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $json_response;
    }


}
