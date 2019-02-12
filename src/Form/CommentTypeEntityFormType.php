<?php

namespace App\Form;

use App\Entity\CommentTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentTypeEntityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentTypeName', TextType::class, array(
                "label" => "评论类型"
            ))
            ->add('commentTypeAlias', TextType::class, array(
                "label" => "类型别名"
            ))
            ->add('commentFilter', TextareaType::class, array(
                "label" => "评论内容允许的html标签",
                "help" => "html标签格式:'<strong><h4>...',如果为空则过滤所有html标签"
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentTypeEntity::class,
        ]);
    }
}
