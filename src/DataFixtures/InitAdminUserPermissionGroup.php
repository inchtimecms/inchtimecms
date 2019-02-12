<?php

namespace App\DataFixtures;

use App\Entity\UserPermissionGroupEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitAdminUserPermissionGroup extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $superAdminGroup = new UserPermissionGroupEntity();
        $superAdminGroup->setGroupName("超级管理员");
        $superAdminGroup->setGroupAlias("super");
        $superAdminGroup->setPermissionJson(array());

        $manager->persist($superAdminGroup);


        $managerGroup = new UserPermissionGroupEntity();
        $managerGroup->setGroupName("管理员");
        $managerGroup->setGroupAlias("admin");
        $managerPermissionJson = '{"menuEntity": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "userEntity": {"view": "on", "Object": ""}, "orderEntity": {"edit": "on", "view": "on", "Object": ""}, "systemEntity": {"view": "on", "Object": ""}, "commentEntity": {"edit": "on", "view": "on", "Object": ""}, "contentEntity": {"new": "on", "page": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "view": "on", "Object": "", "article": {"new": "on", "edit": "on", "view": "on", "Object": ""}}, "taxonomyEntity": {"tags": {"new": "on", "edit": "on", "view": "on", "Object": ""}}, "payMethodEntity": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "shipFeeTemplate": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "userGroupEntity": {"view": "on", "Object": ""}, "fileManageEntity": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "commentTypeEntity": {"new": "on", "edit": "on", "view": "on", "Object": ""}, "contactFormEntity": {"edit": "on", "view": "on", "Object": ""}, "contentTypeEntity": {"new": "on", "page": {"edit": "on", "view": "on", "Object": ""}, "view": "on", "Object": "", "article": {"edit": "on", "view": "on", "Object": ""}}, "productTypeEntity": {"new": "on", "view": "on", "Object": ""}, "taxonomyTypeEntity": {"new": "on", "tags": {"edit": "on", "view": "on", "Object": ""}, "view": "on", "Object": ""}, "productContentEntity": {"new": "on", "view": "on", "Object": ""}, "contactFormTypeEntity": {"new": "on", "edit": "on", "view": "on", "Object": ""}}';
        $managerGroup->setPermissionJson(json_decode($managerPermissionJson, true));

        $manager->persist($managerGroup);


        $simpleUserGroup = new UserPermissionGroupEntity();
        $simpleUserGroup->setGroupName("普通用户");
        $simpleUserGroup->setGroupAlias("user");
        $simpleUserPermissionJson = '{"menuEntity": {"Object": ""}, "userEntity": {"Object": ""}, "orderEntity": {"Object": ""}, "systemEntity": {"Object": ""}, "payMethodEntity": {"Object": ""}, "shipFeeTemplate": {"Object": ""}, "userGroupEntity": {"Object": ""}, "fileManageEntity": {"Object": ""}, "contentTypeEntity": {"mens": {"Object": ""}, "page": {"Object": ""}, "Object": "", "article": {"Object": ""}}, "productTypeEntity": {"Object": ""}, "taxonomyTypeEntity": {"tags": {"Object": ""}, "Object": ""}}';
        $simpleUserGroup->setPermissionJson(json_decode($simpleUserPermissionJson,true));

        $manager->persist($simpleUserGroup);





        $manager->flush();
    }
}
