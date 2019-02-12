<?php

namespace App\Form;

use App\Entity\UserAddressEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consigneeName',TextType::class,array(
                "label" => "收货人姓名"
            ))
            ->add('consigeneePhone',TelType::class,array(
                "label" => "电话"
            ))
            ->add('addressInfo', TextType::class,array(
                "label" => "详细地址"
            ))
            ->add('zipcode',TextType::class,array(
                "label" => "邮编"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAddressEntity::class
        ]);
    }
}
