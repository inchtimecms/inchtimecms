<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactFormTypeEntityRepository")
 */
class ContactFormTypeEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formTypeName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $formTypeAlias;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ContactFormEntity", mappedBy="contactFormTypeEntity")
     */
    private $contactFormEntitys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ContactFormFieldEntity", mappedBy="contactFormTypeEntity", cascade={"persist", "remove"})
     */
    private $contactFormFieldEntities;



    public function __construct()
    {
        $this->contactFormEntitys = new ArrayCollection();
        $this->contactFormFieldEntities = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormTypeName(): ?string
    {
        return $this->formTypeName;
    }

    public function setFormTypeName(string $formTypeName): self
    {
        $this->formTypeName = $formTypeName;

        return $this;
    }

    public function getFormTypeAlias(): ?string
    {
        return $this->formTypeAlias;
    }

    public function setFormTypeAlias(string $formTypeAlias): self
    {
        $this->formTypeAlias = $formTypeAlias;

        return $this;
    }


    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection|ContactFormEntity[]
     */
    public function getContactFormEntitys(): Collection
    {
        return $this->contactFormEntitys;
    }

    public function addContactFormEntity(ContactFormEntity $contactFormEntity): self
    {

        if (!$this->contactFormEntitys->contains($contactFormEntity)) {
            $this->contactFormEntitys[] = $contactFormEntity;
            $contactFormEntity->setContactFormTypeEntity($this);
        }

        return $this;
    }

    public function removeContactFormEntity(ContactFormEntity $contactFormEntity): self
    {
        if ($this->contactFormEntitys->contains($contactFormEntity)) {
            $this->contactFormEntitys->removeElement($contactFormEntity);
            // set the owning side to null (unless already changed)
            if ($contactFormEntity->getContactFormTypeEntity() === $this) {
                $contactFormEntity->setContactFormTypeEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContactFormFieldEntity[]
     */
    public function getContactFormFieldEntities(): Collection
    {
        return $this->contactFormFieldEntities;
    }

    public function addContactFormFieldEntity(ContactFormFieldEntity $contactFormFieldEntity): self
    {

        if (!$this->contactFormFieldEntities->contains($contactFormFieldEntity)) {
            $this->contactFormFieldEntities[] = $contactFormFieldEntity;

//            $this->contactFormFieldEntities->add($contactFormFieldEntity);
            $contactFormFieldEntity->setContactFormTypeEntity($this);
        }

        return $this;
    }

    public function removeContactFormFieldEntity(ContactFormFieldEntity $contactFormFieldEntity): self
    {
        if ($this->contactFormFieldEntities->contains($contactFormFieldEntity)) {
            $this->contactFormFieldEntities->removeElement($contactFormFieldEntity);
            // set the owning side to null (unless already changed)
            if ($contactFormFieldEntity->getContactFormTypeEntity() === $this) {
                $contactFormFieldEntity->setContactFormTypeEntity(null);
            }
        }

        return $this;
    }



}
