<?php

namespace App\Form;

use App\Entity\ContentTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentTypeEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contentTypeName', TextType::class, array(
                "label" => "内容类型名称",
            ))
            ->add('contentTypeMachineAlias', TextType::class, array(
                "label" => "内容类型别名",
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContentTypeEntity::class,
        ]);
    }
}
