<?php

namespace App\Controller\AdminController;

use App\Entity\MenuItemEntity;
use App\Repository\MenuEntityRepository;
use App\Repository\MenuItemEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu/item/entity")
 */
class MenuItemEntityController extends AbstractController
{
    /**
     * AJAX添加菜单项
     * @Route("/new", name="menu_item_new_action", methods={"POST"})
     */
    public function addMenuItem(Request $request, EntityManagerInterface $em,
                                MenuEntityRepository $menuEntityRepository)
    {
        $menuItemName = $request->request->get("menuItemName");
        $menuItemUrl = $request->request->get("menuItemUrl");
        $currMenuId = $request->request->get("currMenu");
        $currMenu = $menuEntityRepository->find($currMenuId);

        $menuItem = new MenuItemEntity();
        $menuItem->setItemName($menuItemName);
        $menuItem->setParentItem(null);
        $menuItem->setMenuEntity($currMenu);
        $menuItem->setItemUrl($menuItemUrl);
        $menuItem->setItemRate(0);

        $em->persist($menuItem);
        $em->flush();

        return $this->json(array("result" => 1));
    }

    /**
     * AJAX编辑菜单项
     * @Route("/edit", name="menu_item_edit_action", methods={"POST"})
     */
    public function editMenuItem(Request $request, MenuItemEntityRepository $menuItemEntityRepository, EntityManagerInterface $em)
    {
        $changeType = $request->request->get("changeType");
        $menuItemName = $request->request->get("menuItemName");
        $menuItemUrl = $request->request->get("menuItemUrl");
        $menuItemId = $request->request->get("menuItemId");
        $menuItemRate = $request->request->get("menuItemRate");

        $menuItemEntity = $menuItemEntityRepository->find($menuItemId);

        if($changeType == "changeInfo"){
            $menuItemEntity->setItemName($menuItemName);
            $menuItemEntity->setItemUrl($menuItemUrl);
        }
        if($changeType == "changeRate"){
            $menuItemEntity->setItemRate($menuItemRate);
        }

        $em->persist($menuItemEntity);

        $em->flush();

        return $this->json(array("result" => 1));
    }

    /**
     * AJAX删除菜单项
     * @Route("/delete", name="menu_item_delete_action", methods={"POST"})
     */
    public function deleteMenuItem(Request $request, EntityManagerInterface $em,
                                   MenuItemEntityRepository $menuItemEntityRepository)
    {
        $menuItemId = $request->request->get("menuItemId");

        $menuItem = $menuItemEntityRepository->find($menuItemId);
        $em->remove($menuItem);
        $em->flush();

        return $this->json(array("result" => 1));
    }
}
