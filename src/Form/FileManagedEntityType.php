<?php

namespace App\Form;

use App\Entity\FileManagedEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileManagedEntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uuid')
            ->add('fileName')
            ->add('uri')
            ->add('filemime')
            ->add('createdAt')
            ->add('changedAt')
            ->add('fileSize')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileManagedEntity::class,
        ]);
    }
}
