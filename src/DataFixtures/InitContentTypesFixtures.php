<?php

namespace App\DataFixtures;

use App\Entity\ContentTypeEntity;
use App\Entity\FieldTypeEntity;
use App\Entity\TaxonomyTypeEntity;
use App\Repository\FieldTypeValueEntityRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitContentTypesFixtures extends Fixture
{
    private $fieldTypeValueEntityRepository;

    public function __construct(FieldTypeValueEntityRepository $fieldTypeValueEntityRepository)
    {
        $this->fieldTypeValueEntityRepository = $fieldTypeValueEntityRepository;
    }

    public function load(ObjectManager $manager)
    {
        //初始化默认的分类标签
        $taxonomyTypeEntity = new TaxonomyTypeEntity();
        $taxonomyTypeEntity->setTaxonomyName("分类标签");
        $taxonomyTypeEntity->setTaxonomyAlias("tags");
        $taxonomyTypeEntity->setTaxonomyDesc("系统初始化创建的分类标签类型.");
        $manager->persist($taxonomyTypeEntity);
        $manager->flush();

        $contentTypeEntity1 = new ContentTypeEntity();
        $contentTypeEntity1->setContentTypeName("基本文章");
        $contentTypeEntity1->setContentTypeMachineAlias("article");
        $contentTypeEntity1->setContentTypeDescription("基本的文章内容类型,包含:标题,内容和分类词汇字段.");
        $contentTypeEntity1->setDeleted("0");
        $manager->persist($contentTypeEntity1);



        //给基本文章类型内容添加引用分类标签字段 .
        $fieldTypeEntity = new FieldTypeEntity();
        $fieldTypeEntity->setFieldName("分类词汇");
        $fieldTypeEntity->setFieldMachineAlias("tags");
        $fieldTypeEntity->setFieldDescription("基本文章类型内容的分类词汇.");

        $taxonomyFieldTypeValue = $this->fieldTypeValueEntityRepository->findOneBy(array("fieldValueTypeName"=>"分类标签"));

        $fieldTypeEntity->setFieldTypeValue($taxonomyFieldTypeValue);
        $fieldTypeEntity->setContentTypeEntity($contentTypeEntity1);
        $fieldTypeEntity->setDeleted(0);
        $fieldTypeEntity->setFieldSettings($taxonomyTypeEntity->getId());
        $manager->persist($fieldTypeEntity);


        $contentTypeEntity2 = new ContentTypeEntity();
        $contentTypeEntity2->setContentTypeName("基本页面");
        $contentTypeEntity2->setContentTypeMachineAlias("page");
        $contentTypeEntity2->setContentTypeDescription("基本的页面内容类型,包含:标题,内容字段.");
        $contentTypeEntity2->setDeleted("0");
        $manager->persist($contentTypeEntity2);


        $manager->flush();

    }
}
