<?php

namespace App\Form;

use App\Entity\ShipFeeTemplateEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipFeeTemplateEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('templateName')
            ->add('province')
            ->add('city')
            ->add('district')
            ->add('shipTimeAfterOrder')
            ->add('shipIsFree')
            ->add('shipMethods')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShipFeeTemplateEntity::class,
        ]);
    }
}
