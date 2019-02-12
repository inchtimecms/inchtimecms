<?php

namespace App\DataFixtures;

use App\Entity\CommentEntity;
use App\Entity\CommentTypeEntity;
use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class InitCommentsExample extends Fixture
{
    public function load(ObjectManager $manager)
    {

//        for ($i = 0; $i < 10; $i++) {
//            $commentEntity = new CommentEntity();
//            $commentEntity->setCommentTypeEntity($this->getReference(InitCommentTypeEntityFixtures::COMMENT_TYPE_REFERENCE));
//            $commentEntity->setAuthor($this->getReference(InitAdminUserSuperAdminFixtures::ADMIN_USER));
//            $commentEntity->setCommentBody("这是一个测试评论".$i);
//            $commentEntity->setCreateAt(new \DateTime());
//
//            $manager->persist($commentEntity);
//
//            sleep(10);
//        }
//
//        $manager->flush();
    }

}
