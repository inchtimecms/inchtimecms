<?php

namespace App\DataFixtures;

use App\Entity\MenuEntity;
use App\Entity\MenuItemEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitMenuFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $menu = new MenuEntity();
        $menu->setMenuName("主菜单");
        $menu->setMenuAlias("main");
        $manager->persist($menu);

        $menuItem = new MenuItemEntity();
        $menuItem->setItemName("主页");
        $menuItem->setItemUrl("/");
        $menuItem->setItemRate(0);
        $menuItem->setMenuEntity($menu);
        $menuItem->setParentItem(null);

        $menuItem1 = new MenuItemEntity();
        $menuItem1->setItemName("伟伟权日记");
        $menuItem1->setItemUrl("http://www.quanweiwei.cn");
        $menuItem1->setItemRate(1);
        $menuItem1->setMenuEntity($menu);
        $menuItem1->setParentItem(null);

        $manager->persist($menuItem);
        $manager->persist($menuItem1);

        $manager->flush();
    }
}
