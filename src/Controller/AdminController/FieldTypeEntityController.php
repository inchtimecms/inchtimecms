<?php

namespace App\Controller\AdminController;

use App\Events;
use App\Entity\FieldTypeEntity;
use App\Form\FieldTypeEntityType;
use App\Entity\FieldTypeValueEntity;
use App\Entity\ContentTypeEntity;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\FieldTypeEntityRepository;
use App\Repository\FieldTypeValueEntityRepository;
use App\Repository\TaxonomyTypeEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @Route("/admin/field/type/entity")
 */
class FieldTypeEntityController extends AbstractController
{
    /**
     * 管理内容类型的字段时，列表显示的当前内容类型所包含的字段。
     * {id}为传过来的内容类型的id，通过此id获取当前内容类型的所有字段collection
     */
    /**
     * @Route("/{id}",requirements={"id": "\d+"}, name="field_type_entity_index", methods="GET|POST")
     */
    public function index(int $id, Request $request): Response
    {
        //根据$id获取当前内容类型对象
        $entityManager = $this->getDoctrine()->getManager();
        $contentTypeEntity = $entityManager->getRepository('App\Entity\ContentTypeEntity')
            ->findOneBy(array('id' => $id));

        return $this->render('admin_pages/field_type_entity/index.html.twig',[
            "contentTypeEntity" => $contentTypeEntity
        ]);
    }

    /**
     * 添加内容类型 转到 添加字段页面form action,用于给对应的内容类型，添加字段。
     * 添加字段之后，创建一个事件处理，根据当前的字段类创建字段表，用于存储当前内容类型下字段的内容。
     */
    /**
     * @Route("/add_field/{contentType}", name="field_type_entity_addfield_action", methods="GET|POST")
     */
    public function addNewFields(string $contentType, Request $request, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em): Response
    {
        $fieldValueId = $request->request->get("fieldValueId");

        $em = $this->getDoctrine()->getManager();

        //因为表此列为1-1，根据此列获取到字段entity
        $fieldTypeValue = $em->getRepository('App\Entity\FieldTypeValueEntity')
            ->findOneBy(array('id' => $fieldValueId));

        //获取当前内容类型的对象
        $contentTypeEntity = $em->getRepository('App\Entity\ContentTypeEntity')
            ->findOneBy(array('contentTypeMachineAlias' => $contentType));


        $fieldName = $request->request->get("fieldName");
        $fieldMachineAlias = $request->request->get("fieldMachineAlias");
        $fieldDescription = $request->request->get("fieldDescription");
        //图片 文件类型时，获取字段设置
        $fieldSetting = $request->request->get("fieldsetting:file_extension");

        //字段不能为空
        if($fieldName != "" && $fieldMachineAlias != ""){
            //创建一个 fieldTypeEntity对象，保存数据吧
            $fieldTypeEntity = new FieldTypeEntity();
            $fieldTypeEntity->setFieldTypeValue($fieldTypeValue);
            $fieldTypeEntity->setFieldName($fieldName);
            $fieldTypeEntity->setFieldMachineAlias($fieldMachineAlias);
            $fieldTypeEntity->setFieldDescription($fieldDescription);
            $fieldTypeEntity->setContentTypeEntity($contentTypeEntity);
            $fieldTypeEntity->setDeleted(0);
            if ($fieldSetting != ""){
                $fieldTypeEntity->setFieldSettings($fieldSetting);
            }

            //引用类型 内容 ，分类标签时，获取name 然后把name中的 引用的id存入 fieldsetting 字段
            //获取所的有的post 参数
            if($fieldTypeValue->getFieldValueTypeName() == "内容" || $fieldTypeValue->getFieldValueTypeName() == "分类标签")
            {
                $refContentOrTagArray = Array();
                $postArgs = $request->request->all();
                foreach ($postArgs as $checkboxName => $checkboxValue){
                    $checkboxNameArray = explode(":",$checkboxName);
                    if($checkboxNameArray[0] == "field_ref"){
                        //把所有选中的引用对象的id存入数组
                        array_push($refContentOrTagArray, $checkboxNameArray[1]);
                    }
                }
                $refContentOrTagIdStr= implode(",", $refContentOrTagArray);

                $fieldTypeEntity->setFieldSettings($refContentOrTagIdStr);
            }
            if($fieldTypeValue->getFieldValueTypeName() == "小数")
            {
                $fieldSetting = $request->request->get("fieldsetting:decimal");
                $fieldTypeEntity->setFieldSettings($fieldSetting);
            }

            if($fieldTypeValue->getFieldValueTypeName() == "布尔值")
            {
                $fieldBoolTrue = $request->request->get("fieldsetting:bool:true");
                $fieldBoolFalse = $request->request->get("fieldsetting:bool:false");

                $fieldTypeEntity->setFieldSettings($fieldBoolTrue.",".$fieldBoolFalse);
            }

            //保存新增字段表
            $em->persist($fieldTypeEntity);
            $em->flush();

        }

        //如果没有添加字段，转到内容类型列表页
//        return $this->redirectToRoute("content_type_entity_index");

        return $this->redirectToRoute("field_type_entity_index",array("id"=> $contentTypeEntity->getId()));
    }


    /**
     * 参数：field_id 获取当前内容类型的FieldTypeEntity对象
     * 设置：要删除的 FieldTypeEntity对象 的deleted字段值为1
     * @Route("/delete_field/{contentTypeEntity_id}/{field_id}", name="field_type_entity_delete", methods="GET|POST")
     */
    public function deleteField(int $contentTypeEntity_id,int $field_id, EntityManagerInterface $em ,FieldTypeEntityRepository $fieldTypeEntityRepository): Response
    {
        //根据$id获取当前内容类型对象
        $fieldTypeEntity = $fieldTypeEntityRepository->findOneBy(array('id' => $field_id));
        $em->remove($fieldTypeEntity);
        $em->flush();

        return $this->redirectToRoute("field_type_entity_index",[
            "id" => $contentTypeEntity_id
        ]);
    }


    /**
     * 功能：render编辑字段页面，编辑完之后，转到当前内容类型的编辑字段列表页
     * 参数：通过ParamConverter把field_id转为FieldTypeEntity对象，用于回显。
     *      $contentTypeEntity_id编辑完回到列表页，要此参数
     * @Route("/edit_page/{contentTypeEntity_id}/{field_id}", name="field_type_entity_edit", methods="GET|POST")
     * @ParamConverter("fieldTypeEntity", class="App\Entity\FieldTypeEntity", options={"id" = "field_id"})
     */
    public function renderEditFieldPage(FieldTypeEntity $fieldTypeEntity, int $contentTypeEntity_id ): Response
    {
        $fieldRefType = $fieldTypeEntity->getFieldTypeValue()->getFieldValueTypeName();
        $refTypeEntityArray = "";
        if($fieldRefType == "内容" || $fieldRefType == "分类标签")
        {
            $refTypeEntityIds = $fieldTypeEntity->getFieldSettings();
            $refTypeEntityArray = explode(",",$refTypeEntityIds);
        }

        $em = $this->getDoctrine()->getManager();

        $contentTypeEntityRepo = $em->getRepository("App\Entity\ContentTypeEntity");
        $contentTypeEntitys = $contentTypeEntityRepo->findAll();

        $taxonomyTypeEntityRepo = $em->getRepository("App\Entity\TaxonomyTypeEntity");
        $taxonomyTypeEntitys = $taxonomyTypeEntityRepo->findAll();

        return $this->render('admin_pages/field_type_entity/edit.html.twig',[
            "contentTypeEntity_id" => $contentTypeEntity_id,
            "fieldTypeEntity" => $fieldTypeEntity,
            "refTypeEntityArray" => $refTypeEntityArray,
            "contentTypeEntitys" => $contentTypeEntitys,
            "taxonomyTypeEntitys" => $taxonomyTypeEntitys
        ]);
    }

    /**
     * 参数：field_id 获取当前内容类型的FieldTypeEntity对象
     * 功能：获取编辑FieldTypeEntity对象，保存到库
     * @Route("/edit_field/{contentTypeEntity_id}/{field_id}", name="field_type_entity_edit_action", methods="GET|POST")
     * @ParamConverter("fieldTypeEntity", class="App\Entity\FieldTypeEntity", options={"id" = "field_id"})
     */
    public function editField(Request $request, FieldTypeEntity $fieldTypeEntity, int $contentTypeEntity_id ): Response
    {
        $newFieldName = $request->request->get("fieldName");
        $newFieldDescription = $request->request->get("fieldDescription");
        $newFieldSettings = $request->request->get("fieldsetting:file_extension");

        $fieldTypeEntity->setFieldName($newFieldName);
        $fieldTypeEntity->setFieldDescription($newFieldDescription);
        $fieldTypeEntity->setDeleted(0);
        if ($newFieldSettings != ""){
            $fieldTypeEntity->setFieldSettings($newFieldSettings);
        }

        //引用类型 内容 ，分类标签时，获取name 然后把name中的 引用的id存入 fieldsetting 字段
        //获取所的有的post 参数
        if($fieldTypeEntity->getFieldTypeValue()->getFieldValueTypeName() == "内容"
            || $fieldTypeEntity->getFieldTypeValue()->getFieldValueTypeName() == "分类标签")
        {
            $refContentOrTagArray = Array();
            $postArgs = $request->request->all();
            foreach ($postArgs as $checkboxName => $checkboxValue){
                $checkboxNameArray = explode(":",$checkboxName);
                if($checkboxNameArray[0] == "field_ref"){
                    //把所有选中的引用对象的id存入数组
                    array_push($refContentOrTagArray, $checkboxNameArray[1]);
                }
            }
            $refContentOrTagIdStr= implode(",", $refContentOrTagArray);

            $fieldTypeEntity->setFieldSettings($refContentOrTagIdStr);
        }

        if($fieldTypeEntity->getFieldTypeValue()->getFieldValueTypeName() == "小数")
        {
            $fieldSetting = $request->request->get("fieldsetting:decimal");
            $fieldTypeEntity->setFieldSettings($fieldSetting);
        }

        if($fieldTypeEntity->getFieldTypeValue()->getFieldValueTypeName() == "布尔值")
        {
            $fieldBoolTrue = $request->request->get("fieldsetting:bool:true");
            $fieldBoolFalse = $request->request->get("fieldsetting:bool:false");

            $fieldTypeEntity->setFieldSettings($fieldBoolTrue.",".$fieldBoolFalse);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fieldTypeEntity);
        $entityManager->flush();


        return $this->redirectToRoute("field_type_entity_index",[
            "id" => $contentTypeEntity_id
        ]);
    }

    /**
     * 判断当前新填写的字段的机器别名是否已存在.此方法用于Ajax
     * 返回值：如果不存在则返回"0"，如果存在返回"1"。
     * @Route("/alias_unique", name="field_type_entity_alias_unique", methods="GET|POST")
     */
    public function aliasUnique(Request $request, FieldTypeEntityRepository $fieldTypeEntityRepository): Response
    {
        $fieldAlias = $request->request->get("field_alias");

        $fieldTypeEntity = $fieldTypeEntityRepository->findOneBy(array('fieldMachineAlias' => $fieldAlias));

        return new Response($fieldTypeEntity == null ? "0" : "1");
    }


}
