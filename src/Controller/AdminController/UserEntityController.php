<?php

namespace App\Controller\AdminController;

use App\Entity\UserEntity;
use App\Form\UserEntityType;
use App\Repository\UserEntityRepository;
use App\Repository\UserPermissionGroupEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class UserEntityController extends AbstractController
{
    /**
     * @Route("/user/entity/list", name="user_entity_list")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_ENTITY_VIEW);

        $userEntitys = $em->createQuery("SELECT u FROM App\Entity\UserEntity u WHERE u.id != :currUser AND u.username != :admin ORDER BY u.id DESC")
            ->setParameter("currUser", $this->getUser())
            ->setParameter("admin", "admin")
            ->getResult();

        $pagination = $paginator->paginate(
            $userEntitys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('admin_pages/user_entity/index.html.twig', [
            "pagination" => $pagination
        ]);
    }

    /**
     * @Route("/user/entity/edit/{id}", name="user_entity_edit")
     */
    public function editUser(Request $request, UserEntity $userEntity, UserPasswordEncoderInterface $passwordEncoder,
                             UserPermissionGroupEntityRepository $groupEntityRepository, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_ENTITY_EDIT);

        $userForm = $this->createForm(UserEntityType::class,$userEntity);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()){
            /** @var UserEntity $userData **/
            $userData = $userForm->getData();

            /**@var boolean $enabled**/
            $enabled = $request->request->get("enabled");

            if ($enabled != null){
                $userData->setEnabled($enabled);
            }

            $userGroupId = $request->request->get("userPermissionGroupEntity");
            if ($userGroupId != null){
                $userGroupEntity = $groupEntityRepository->find($userGroupId);
                $userData->setUserPermissionGroupEntity($userGroupEntity);

                $groupAlias = $userGroupEntity->getGroupAlias();
                if ($groupAlias !== "user" && !in_array("ROLE_SUPER_ADMIN",$userData->getRoles())){
                    $userData->setRoles(array("ROLE_ADMIN"));
                }else{
                    $userData->setRoles(array("ROLE_USER"));
                }

            }

            $newPassword = $request->request->get("new_password");
            $newPasswordRepeat = $request->request->get("new_password_repeat");
            if ($newPassword == $newPasswordRepeat && $newPassword != "" && $newPasswordRepeat != ""){
                $password = $passwordEncoder->encodePassword($userData, $newPassword);
                $userData->setPassword($password);
            }

            $entityManager->persist($userData);
            $entityManager->flush();

            return $this->redirectToRoute("user_entity_list");
        }

        //如果当前用户是超级管理员
        /**@var UserEntity $currUser**/
        $currUser = $this->getUser();
        if ($currUser->getUserPermissionGroupEntity()->getGroupAlias() == "super"){
            $userPermissionGroups = $groupEntityRepository->findAll();
        }else{
            $userPermissionGroups = $groupEntityRepository->findBy(array("groupAlias" => array("admin","user")));
        }
        return $this->render("admin_pages/user_entity/profile.html.twig",[
            "userForm" => $userForm->createView(),
            "userEntity" => $userEntity,
            "userPermissionGroups" => $userPermissionGroups
        ]);
    }

    /**
     * @Route("/user/entity/disable/{id}", name="user_entity_disable")
     */
    public function disable(UserEntity $userEntity, EntityManagerInterface $entityManager)
    {
        $userEntity->setEnabled(false);
        $entityManager->persist($userEntity);
        $entityManager->flush();

        return $this->redirectToRoute("user_entity_list");
    }

    /**
     * @Route("/user/entity/delete/{id}", name="user_entity_delete")
     */
    public function delete(UserEntity $userEntity, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::USER_ENTITY_DELETE);
        $entityManager->remove($userEntity);
        $entityManager->flush();

        return $this->redirectToRoute("user_entity_list");
    }
}
