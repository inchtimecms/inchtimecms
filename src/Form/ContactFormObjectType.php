<?php

namespace App\Form;

use App\Entity\ContactFormEntity;
use App\Entity\ContactFormTypeEntity;
use App\Repository\ContactFormTypeEntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormObjectType extends AbstractType
{
    private $contactFormTypeRepo;

    public function __construct(ContactFormTypeEntityRepository $repository)
    {
        $this->contactFormTypeRepo = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options["contact_form_type_id"])) {
            /**@var ContactFormTypeEntity $contactFormTypeEntity * */
            $contactFormTypeEntity = $this->contactFormTypeRepo->find($options["contact_form_type_id"]);

            $contactFormFields = $contactFormTypeEntity->getContactFormFieldEntities();
            $index = 0;
            foreach ($contactFormFields as $contactFormField) {
                $index++;
                $builder
                    ->add("label".$index,
                        $contactFormField->getFormFieldType(),
                        array(
                            "label" => $contactFormField->getFormFieldLabel(),
                        )
                    );

            }

//            $builder->add("contact_form_type",HiddenType::class, array(
//                "attr"=>array(
//                    "value" => $options["contact_form_type_id"],
//                ),
//            ));

        }



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
//            "data_class" => ContactFormEntity::class,
        ]);
        $resolver->setDefined(array(
            "contact_form_type_id",
        ));
    }
}
