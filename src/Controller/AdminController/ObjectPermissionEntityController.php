<?php

namespace App\Controller\AdminController;

use App\Entity\ObjectPermissionEntity;
use App\Entity\UserPermissionGroupEntity;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\ProductTypeEntityRepository;
use App\Repository\TaxonomyTypeEntityRepository;
use App\Repository\UserPermissionGroupEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

/**
 * @Route("/admin/object/permission/entity")
 */
class ObjectPermissionEntityController extends AbstractController
{
    /**
     * @Route("/", name="object_permission_entity")
     * 显示所有的权限类型
     */
//    public function index(ContentTypeEntityRepository $contentTypeEntityRepository,
//                          TaxonomyTypeEntityRepository $taxonomyTypeEntityRepository,
//                          ProductTypeEntityRepository $productTypeEntityRepository)
//    {
//        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_GROUP_ENTITY_VIEW);
//        //内容类型的权限：内容类型是否可以添加 修改 删除，每个类型的内容是否可以 添加 修改 删除
//        $contentTypeEntitys = $contentTypeEntityRepository->findBy(array("deleted" => 0));
//
//        //内容的权限：允许添加的内容类型的自己的内容是否可以修改 删除，他人的内容是否可以修改 删除，
//
//        //分类标签类型的权限：标签权限是否可以查看 添加 修改 删除 分类词汇： 是否可以查看 添加 修改 删除
//        $taxonomyTypes = $taxonomyTypeEntityRepository->findAll();
//
//        //文件管理：是否可以查看 添加 修改 删除，他人的文件是否可以查看 修改 删除
//
//        //商品类型： 是否可以查看 添加 修改 删除 商品类型 ， 每个类型的商品 是否 可以查看 添加 修改 删除
//        $productTypeEntitys = $productTypeEntityRepository->findAll();
//        //运费设置： 是否可以查看 添加 修改 删除
//
//        //付款方式： 是否可以查看 添加 修改 删除
//
//        //订单管理： 是否可以查看 修改 删除
//
//        //菜单管理： 是否可以查看 添加 修改 删除
//
//        //评论类型： 是否可以查看 添加 修改 删除
//
//        //评论内容： 是否可以查看 修改 删除
//
//        //联系表单： 是否可以查看 添加 修改 删除
//
//        //表单内容： 是否可以查看 修改 删除
//
//        //用户列表： 是否可以查看 修改 删除
//
//        //用户组：   是否可以查看 添加 修改 删除
//
//        //系统设置： 是否可以查看 修改 删除
//
//      return $this->render('admin_pages/object_permission_entity/index.html.twig', [
//          "contentTypeEntitys" => $contentTypeEntitys,
//          "taxonomyTypes" => $taxonomyTypes,
//          "productTypeEntitys" => $productTypeEntitys,
//        ]);
//    }

//    /**
//     * @Route("/add", name="object_permission_entity_add", methods={"post"})
//     */
//    public function addObjPermission(Request $request,EntityManagerInterface $em,
//                                     UserPermissionGroupEntityRepository $groupEntityRepository){
//
//        $userPermissionGroupId = $request->request->get("userPermissionGroupEntityId");
//
//        $token = $request->request->get("_token");
//        if (!$this->isCsrfTokenValid("user_permission",$token)){
//             return $this->redirectToRoute("object_permission_entity");
//        }
//
//        $request->request->remove("_token");
//        $permissionData = $request->request->all();
//
//        $objectPermission = new ObjectPermissionEntity();
//        $objectPermission->setPermissionJson($permissionData);
//        $em->persist($objectPermission);
//
//        $groupEntity = $groupEntityRepository->find($userPermissionGroupId);
//        $groupEntity->setObjectPermissionEntity($objectPermission);
//        $em->persist($groupEntity);
//
//        $em->flush();
//
//        return $this->redirectToRoute("user_permission_group_entity");
//    }

}
