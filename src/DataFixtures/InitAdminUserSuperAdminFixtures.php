<?php

namespace App\DataFixtures;

use App\Entity\UserEntity;
use App\Repository\UserPermissionGroupEntityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Util\UserManipulator;

class InitAdminUserSuperAdminFixtures extends Fixture
{
    private $userManipulator;
    private $groupEntityRepository;
    public const ADMIN_USER = "admin";

    public function __construct(UserManipulator $userManipulator, UserPermissionGroupEntityRepository $groupEntityRepository)
    {
        $this->userManipulator = $userManipulator;
        $this->groupEntityRepository = $groupEntityRepository;
    }

    public function load(ObjectManager $manager)
    {
        /**@var UserEntity $admin **/
        $admin = $this->userManipulator->create("admin","admin","admin@admin.com",true,true);

        $superAdminGroup = $this->groupEntityRepository->findOneBy(array("groupAlias"=>"superAdmin"));
        $admin->setUserPermissionGroupEntity($superAdminGroup);
        $manager->persist($admin);
        $manager->flush();

        $this->addReference(self::ADMIN_USER, $admin);
    }
}
