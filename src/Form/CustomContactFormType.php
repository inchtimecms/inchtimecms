<?php

namespace App\Form;

use App\Entity\ContactFormTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//创建表单类型的FormType
class CustomContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formTypeName', TextType::class, array(
                "label" => "表单类型名称",
            ))
            ->add('formTypeAlias', TextType::class, array(
                "label" => "表单类型别名",
            ))
            ->add("contactFormFieldEntities", CollectionType::class, array(
                "entry_type" => ContactFormFieldType::class,
                "entry_options"=>array("label" => false),
                "allow_add" => true,
                "by_reference" => false,
                "allow_delete" => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactFormTypeEntity::class,
            'allow_extra_fields' => true,
        ]);
    }
}
