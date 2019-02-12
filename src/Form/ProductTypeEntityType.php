<?php

namespace App\Form;

use App\Entity\ProductTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypeEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productTypeName')
            ->add('productDesc')
            ->add('priceField')
            ->add('discountPriceField')
            ->add('salePropField')
            ->add('payMethods')
            ->add('boolRealOrVirtual')
            ->add('boolNeedShip')
            ->add('contentTypeEntity')
            ->add('shipFeeTemplateEntity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductTypeEntity::class,
        ]);
    }
}
