<?php

namespace App\DataFixtures;

use App\Entity\PayMethodEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitPayMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $alipayMethod = new PayMethodEntity();
        $alipayMethod->setPayMethodName("支付宝");
        $alipayMethod->setPayMethodAlias("alipay");
        $alipayMethod->setPayMethodDesc("使用支付宝付款");
        $manager->persist($alipayMethod);

        $wxPayMethod = new PayMethodEntity();
        $wxPayMethod->setPayMethodName("微信支付");
        $wxPayMethod->setPayMethodAlias("wxpay");
        $wxPayMethod->setPayMethodDesc("使用微信支付付款");
        $manager->persist($wxPayMethod);

        $manager->flush();
    }
}
