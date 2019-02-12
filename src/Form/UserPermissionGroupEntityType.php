<?php

namespace App\Form;

use App\Entity\UserPermissionGroupEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPermissionGroupEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupName', TextType::class, array(
                "label" => "组名(唯一)"
            ))
            ->add('groupAlias', TextType::class, array(
                "label" => "英文别名(唯一)",
                "required" => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPermissionGroupEntity::class,
        ]);
    }
}
