<?php

namespace App\DataFixtures;

use App\Entity\SystemEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitSystemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $system = new SystemEntity();

        $system->setSiteTitle("InchTimeCMS");
        $system->setSiteSubTitle("InchTimeCMS是一款可配置,高扩展的内容管理系统,您可以使用它进行自由的创作.");
        $system->setSiteEmail("admin@inchtime.cn");
        $system->setSiteDescription("INCHTIMECMS是一款可配置,高扩展的内容管理系统,您可以使用它进行自由的创作.无论是博客,资讯网站,还是在线商城都可以使用INCHTIMECMS轻松构建。");
        $manager->persist($system);

        $manager->flush();
    }
}
