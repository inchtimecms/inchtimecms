<?php

namespace App\Form;

use App\Entity\ContactFormFieldEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formFieldLabel', TextType::class, array(
                "label" => "表单字段标签",
                "attr" => array(
                    "placeholder" => "请输入表单字段的标签",
                ),
            ))
            ->add("formFieldType", ChoiceType::class,array(
                "choices" => array(
                    "单行文本" => TextType::class,
                    "多行文本" => TextareaType::class,
                    "邮箱文本" => EmailType::class,
                    "电话文本" => TelType::class,
                    "网址文本" => UrlType::class,
                    "整数文本" => IntegerType::class,
                ),
                "label" => "表单字段类型",
                "attr" => array(
                ),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            "data_class"=>ContactFormFieldEntity::class,
        ]);
    }
}
