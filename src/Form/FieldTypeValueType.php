<?php

namespace App\Form;

use App\Entity\FieldTypeValueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldTypeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldTypeValueType')
            ->add('fieldValueTypeName')
            ->add('fieldTypeInSQL')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldTypeValueEntity::class,
        ]);
    }
}
