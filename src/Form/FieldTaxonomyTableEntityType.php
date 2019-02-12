<?php

namespace App\Form;

use App\Entity\FieldTaxonomyTableEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldTaxonomyTableEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createAt')
            ->add('contentEntity')
            ->add('taxonomyEntity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldTaxonomyTableEntity::class,
        ]);
    }
}
