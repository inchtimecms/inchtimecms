<?php

namespace App\Controller\AdminController;


use App\Entity\MenuEntity;
use App\Repository\ContactFormEntityRepository;
use App\Repository\ContactFormTypeEntityRepository;
use App\Repository\ContentTypeEntityRepository;
use App\Repository\MenuEntityRepository;
use App\Repository\TaxonomyEntityRepository;
use App\Security\Voter\UserPermissionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function index(Request $request,
                          ContactFormTypeEntityRepository $contactFormTypeEntityRepository,
                          ContentTypeEntityRepository $contentTypeEntityRepository,
                          TaxonomyEntityRepository $taxonomyEntityRepository,
                          MenuEntityRepository $menuEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::MENU_ENTITY_VIEW);

        $menus = $menuEntityRepository->findAll();

        $menuId = $request->query->get("menu");
        if ($menuId != null) {
            $menu = $menuEntityRepository->findOneBy(["id" => $menuId]);
        } else {
            $menu = $menuEntityRepository->findOneBy([], ["id" => "ASC"]);
        }

        $menuItemEntitys = $menu->getMenuItemEntitys();

        $contentTypeEntitys = $contentTypeEntityRepository->findAll();

        $taxonomyEntitys = $taxonomyEntityRepository->findAll();

        $contactFormTypeEntitys = $contactFormTypeEntityRepository->findAll();

        return $this->render('admin_pages/menu/index.html.twig', [
            "rootUrl" => $request->getSchemeAndHttpHost(),
            "menus" => $menus,
            "menu" => $menu,
            "menuItemEntitys" => $menuItemEntitys,
            "contentTypeEntitys" => $contentTypeEntitys,
            "taxonomyEntitys" => $taxonomyEntitys,
            "contactFormTypeEntitys" => $contactFormTypeEntitys
        ]);
    }

    /**
     * @Route("/menu/new", name="menu_new")
     */
    public function newMenu(Request $request,
                            ContentTypeEntityRepository $contentTypeEntityRepository,
                            TaxonomyEntityRepository $taxonomyEntityRepository,
                            MenuEntityRepository $menuEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::MENU_ENTITY_NEW);

        $menus = $menuEntityRepository->findAll();

        $contentTypeEntitys = $contentTypeEntityRepository->findAll();

        $taxonomyEntitys = $taxonomyEntityRepository->findAll();

        return $this->render('admin_pages/menu/new.html.twig', [
            "rootUrl" => $request->getSchemeAndHttpHost(),
            "menus" => $menus,
            "contentTypeEntitys" => $contentTypeEntitys,
            "taxonomyEntitys" => $taxonomyEntitys
        ]);
    }

    /**
     * @Route("/menu/new/action", name="menu_new_action", methods={"POST"})
     */
    public function newMenuAction(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::MENU_ENTITY_NEW);

        $menuName = $request->request->get("menu-name");
        $menuAlias = $request->request->get("menu-alias");

        $menu = new MenuEntity();
        $menu->setMenuName($menuName);
        $menu->setMenuAlias($menuAlias);

        $em->persist($menu);
        $em->flush();

        return $this->redirectToRoute("menu",array("menu"=>$menu->getId()));
    }

    /**
     * Ajax修改菜单名称
     * @Route("/menu/edit", name="menu_edit_action", methods={"POST"})
     */
    public function editMenu(Request $request, EntityManagerInterface $em,
                             MenuEntityRepository $menuEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::MENU_ENTITY_EDIT);
        $menuId = $request->request->get("menuId");
        $menuName = $request->request->get("menuName");

        $menuEntity = $menuEntityRepository->find($menuId);
        $menuEntity->setMenuName($menuName);

        $em->persist($menuEntity);
        $em->flush();

        return $this->json(array("result" => 1));
    }

    /**
     * @Route("/menu/delete", name="menu_delete_action", methods={"POST"})
     */
    public function deleteMenu(Request $request, EntityManagerInterface $em,
                               MenuEntityRepository $menuEntityRepository)
    {
        $this->denyAccessUnlessGranted(UserPermissionVoter::MENU_ENTITY_DELETE);

        $menuId = $request->request->get("menuId");

        $menu = $menuEntityRepository->find($menuId);

        //删除菜单 先删除菜单项
        $menuItems = $menu->getMenuItemEntitys();
        foreach ($menuItems as $menuItem) {
            $em->remove($menuItem);
        }
        //删除菜单
        $em->remove($menu);

        $em->flush();

        return $this->json(array("result" => 1));
    }
}
