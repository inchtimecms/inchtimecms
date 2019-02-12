<?php

namespace App\Controller\AdminController;

use App\Entity\UserPermissionGroupEntity;
use App\Form\UserPermissionGroupEntityType;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use App\Repository\TaxonomyTypeEntityRepository;
use App\Repository\UserPermissionGroupEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/user/permission/group/entity")
 */
class UserPermissionGroupEntityController extends AbstractController
{
    /**
     * @Route("/list", name="user_permission_group_entity")
     */
    public function index(UserPermissionGroupEntityRepository $groupEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_VIEW);

        $userPermissionGroups = $groupEntityRepository->findAll();
        return $this->render('admin_pages/user_permission_group_entity/index.html.twig', [
            "userPermissionGroups" =>$userPermissionGroups
        ]);
    }

    /**
     * @Route("/add", name="add_user_permission_group")
     */
    public function add(Request $request, EntityManagerInterface $em,
                        ContentTypeEntityRepository $contentTypeEntityRepository,
                        TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository,
                        ProductTypeEntityRepository $productTypeEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_NEW);
        //内容类型的权限：内容类型是否可以添加 修改 删除，每个类型的内容是否可以 添加 修改 删除
        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));

        //内容的权限：允许添加的内容类型的自己的内容是否可以修改 删除，他人的内容是否可以修改 删除，

        //分类标签类型的权限：标签权限是否可以查看 添加 修改 删除 分类词汇： 是否可以查看 添加 修改 删除
        $taxonomyTypes = $taxonomyTypeEntityRepository->findAll();

        //文件管理：是否可以查看 添加 修改 删除，他人的文件是否可以查看 修改 删除

        //商品类型： 是否可以查看 添加 修改 删除 商品类型 ， 每个类型的商品 是否 可以查看 添加 修改 删除
        $productTypeEntitys = $productTypeEntityRepository->findAll();

        $form = $this->createForm(UserPermissionGroupEntityType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UserPermissionGroupEntity $data **/
            $data = $form->getData();

            $request->request->remove("user_permission_group_entity");
            $permissionData = $request->request->all();
            $data->setPermissionJson($permissionData);

            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute("user_permission_group_entity");
        }


        return $this->render('admin_pages/user_permission_group_entity/add.html.twig', [
            "form" => $form->createView(),
            "contentTypeEntitys" => $contentTypeEntitys,
            "taxonomyTypes" => $taxonomyTypes,
            "productTypeEntitys" => $productTypeEntitys,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_user_permission_group")
     */
    public function edit(Request $request, EntityManagerInterface $em,
                         UserPermissionGroupEntity $userPermissionGroupEntity,
                         ContentTypeEntityRepository $contentTypeEntityRepository,
                         TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository,
                         ProductTypeEntityRepository $productTypeEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_EDIT);

        //内容类型的权限：内容类型是否可以添加 修改 删除，每个类型的内容是否可以 添加 修改 删除
        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));

        //内容的权限：允许添加的内容类型的自己的内容是否可以修改 删除，他人的内容是否可以修改 删除，

        //分类标签类型的权限：标签权限是否可以查看 添加 修改 删除 分类词汇： 是否可以查看 添加 修改 删除
        $taxonomyTypes = $taxonomyTypeEntityRepository->findAll();

        //文件管理：是否可以查看 添加 修改 删除，他人的文件是否可以查看 修改 删除

        //商品类型： 是否可以查看 添加 修改 删除 商品类型 ， 每个类型的商品 是否 可以查看 添加 修改 删除
        $productTypeEntitys = $productTypeEntityRepository->findAll();
        $form = $this->createForm(UserPermissionGroupEntityType::class, $userPermissionGroupEntity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            /** @var UserPermissionGroupEntity $data **/
            $data = $form->getData();

            $request->request->remove("user_permission_group_entity");
            $permissionData = $request->request->all();
            $data->setPermissionJson($permissionData);

            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute("user_permission_group_entity");
        }

        return $this->render('admin_pages/user_permission_group_entity/edit.html.twig', [
            "form" => $form->createView(),
            "contentTypeEntitys" => $contentTypeEntitys,
            "taxonomyTypes" => $taxonomyTypes,
            "productTypeEntitys" => $productTypeEntitys,
            "permissionJson" => $userPermissionGroupEntity->getPermissionJson(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_user_permission_group")
     */
    public function delete(UserPermissionGroupEntity $userPermissionGroupEntity, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_DELETE);
        $userEntitys = $userPermissionGroupEntity->getAdminUser();
        foreach ($userEntitys as $userEntity){
            $userEntity->setUserPermissionGroupEntity(null);
            $em->persist($userEntity);
        }

        $em->remove($userPermissionGroupEntity);
        $em->flush();
        return $this->redirectToRoute("user_permission_group_entity");
    }
}
