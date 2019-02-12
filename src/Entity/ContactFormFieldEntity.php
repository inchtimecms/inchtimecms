<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactFormFieldEntityRepository")
 */
class ContactFormFieldEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactFormTypeEntity", inversedBy="contactFormFieldEntities")
     */
    private $contactFormTypeEntity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formFieldLabel;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formFieldType;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactFormTypeEntity(): ?ContactFormTypeEntity
    {
        return $this->contactFormTypeEntity;
    }

    public function setContactFormTypeEntity(?ContactFormTypeEntity $contactFormTypeEntity): self
    {
        $this->contactFormTypeEntity = $contactFormTypeEntity;

        return $this;
    }

    public function getFormFieldLabel(): ?string
    {
        return $this->formFieldLabel;
    }

    public function setFormFieldLabel(string $formFieldLabel): self
    {
        $this->formFieldLabel = $formFieldLabel;

        return $this;
    }

    public function getFormFieldType(): ?string
    {
        return $this->formFieldType;
    }

    public function setFormFieldType(string $formFieldType): self
    {
        $this->formFieldType = $formFieldType;

        return $this;
    }

}
