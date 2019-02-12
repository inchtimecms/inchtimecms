<?php

namespace App\DataFixtures;

use App\Entity\ShipFeeTemplateEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitShipFeeTemplateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $shipFeeTemplate = new ShipFeeTemplateEntity();
        $shipFeeTemplate->setTemplateName("全国包邮");
        $shipFeeArray = '[{"shipexpress": {"shipexpress": 1}}, {"shipems": {"shipems": 1}}]';
        $shipFeeTemplate->setShipMethods(json_decode($shipFeeArray,true));
        $shipFeeTemplate->setProvince("河南省");
        $shipFeeTemplate->setCity("洛阳市");
        $shipFeeTemplate->setDistrict("吉利区");
        $shipFeeTemplate->setShipIsFree(true);
        $shipFeeTemplate->setShipTimeAfterOrder("1天内");

        $manager->persist($shipFeeTemplate);
        $manager->flush();
    }
}
