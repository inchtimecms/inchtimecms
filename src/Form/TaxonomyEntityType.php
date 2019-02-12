<?php

namespace App\Form;

use App\Entity\TaxonomyEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxonomyEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxonomyWord')
            ->add('taxonomyDesc')
            ->add('weight')
            ->add('changedAt')
            ->add('taxonomyTypeEntity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TaxonomyEntity::class,
        ]);
    }
}
