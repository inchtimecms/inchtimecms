<?php

namespace App\DataFixtures;

use App\Entity\FieldTypeValueEntity;
use App\Entity\TaxonomyTypeEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InitAFieldTypeValueFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        //初始化系统自带的字段类型,fieldTypeValue
        $fieldTypeValue01 = new FieldTypeValueEntity();
        $fieldTypeValue01->setFieldValueTypeName("内容");
        $fieldTypeValue01->setFieldTypeValueType("引用");
        $fieldTypeValue01->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue01);

        $fieldTypeValue02 = new FieldTypeValueEntity();
        $fieldTypeValue02->setFieldValueTypeName("分类标签");
        $fieldTypeValue02->setFieldTypeValueType("引用");
        $fieldTypeValue02->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue02);

        $fieldTypeValue03 = new FieldTypeValueEntity();
        $fieldTypeValue03->setFieldValueTypeName("文件");
        $fieldTypeValue03->setFieldTypeValueType("引用");
        $fieldTypeValue03->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue03);

        $fieldTypeValue04 = new FieldTypeValueEntity();
        $fieldTypeValue04->setFieldValueTypeName("图像");
        $fieldTypeValue04->setFieldTypeValueType("引用");
        $fieldTypeValue04->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue04);

        $fieldTypeValue05 = new FieldTypeValueEntity();
        $fieldTypeValue05->setFieldValueTypeName("用户");
        $fieldTypeValue05->setFieldTypeValueType("引用");
        $fieldTypeValue05->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue05);

        $fieldTypeValue06 = new FieldTypeValueEntity();
        $fieldTypeValue06->setFieldValueTypeName("布尔值");
        $fieldTypeValue06->setFieldTypeValueType("常规设置");
        $fieldTypeValue06->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue06);

        $fieldTypeValue07 = new FieldTypeValueEntity();
        $fieldTypeValue07->setFieldValueTypeName("日期");
        $fieldTypeValue07->setFieldTypeValueType("常规设置");
        $fieldTypeValue07->setFieldTypeInSQL("datetime");
        $manager->persist($fieldTypeValue07);

        $fieldTypeValue08 = new FieldTypeValueEntity();
        $fieldTypeValue08->setFieldValueTypeName("小数");
        $fieldTypeValue08->setFieldTypeValueType("常规设置");
        $fieldTypeValue08->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue08);

        $fieldTypeValue09 = new FieldTypeValueEntity();
        $fieldTypeValue09->setFieldValueTypeName("纯文本");
        $fieldTypeValue09->setFieldTypeValueType("常规设置");
        $fieldTypeValue09->setFieldTypeInSQL("string");
        $manager->persist($fieldTypeValue09);

        $fieldTypeValue10 = new FieldTypeValueEntity();
        $fieldTypeValue10->setFieldValueTypeName("长纯文本");
        $fieldTypeValue10->setFieldTypeValueType("常规设置");
        $fieldTypeValue10->setFieldTypeInSQL("text");
        $manager->persist($fieldTypeValue10);

        $manager->flush();
    }

}
