<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactFormEntityRepository")
 */
class ContactFormEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"id"="DESC"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactFormTypeEntity", inversedBy="contactFormEntitys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contactFormTypeEntity;

    /**
     * @ORM\Column(type="json")
     */
    private $contactFormData = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;


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

    public function getContactFormData(): ?array
    {
        return $this->contactFormData;
    }

    public function setContactFormData(array $contactFormData): self
    {
        $this->contactFormData = $contactFormData;

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

}
