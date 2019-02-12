<?php

namespace App\Form;

use App\Entity\SystemEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siteTitle',TextType::class,array(
                "label" => "站点标题"
            ))
            ->add('siteSubTitle',TextType::class, array(
                "label" => "站点副标题"
            ))
            ->add('siteDescription',TextareaType::class, array(
                "label" => "站点描述"
            ))
            ->add('siteEmail', EmailType::class, array(
                "label" => "电子邮件地址"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            "data_class" => SystemEntity::class,
        ]);
    }
}
