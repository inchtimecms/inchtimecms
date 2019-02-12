<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FieldContentTableEntityRepository")
 */
class FieldContentTableEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldContentTableEntitys")
     * @ORM\JoinColumn(name="content_entity_id")
     */
    private $contentEntity;

    /**
     * @ORM\ManyToOne(targetEntity="ContentEntity")
     * @ORM\JoinColumn(name="ref_content_entity_id")
     */
    private $refContentEntity;

    /**
     * @ORM\Column(type="string")
     */
    private $fieldAlias;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    public function getId()
    {
        return $this->id;
    }

    public function getFieldAlias(): ?string
    {
        return $this->fieldAlias;
    }

    public function setFieldAlias(string $fieldAlias): self
    {
        $this->fieldAlias = $fieldAlias;

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

    public function getContentEntity(): ?ContentEntity
    {
        return $this->contentEntity;
    }

    public function setContentEntity(?ContentEntity $contentEntity): self
    {
        $this->contentEntity = $contentEntity;

        return $this;
    }

    public function getRefContentEntity(): ?ContentEntity
    {
        return $this->refContentEntity;
    }

    public function setRefContentEntity(?ContentEntity $refContentEntity): self
    {
        $this->refContentEntity = $refContentEntity;

        return $this;
    }
}
