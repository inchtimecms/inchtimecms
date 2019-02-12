<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentTypeEntityRepository")
 * 内容类型Entity，用来存储每个内容类型所包含的字段、字段类型。
 */
class ContentTypeEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false))
     * @Assert\NotBlank
     */
    private $contentTypeName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true))
     * @Assert\NotBlank
     */
    private $contentTypeDescription;

    /**
     * 内容类型的机器别名，小写英文，可以用于获取当前内容类型的列表
     */
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=false))
     * @Assert\NotBlank
     */
    private $contentTypeMachineAlias;

    /**
     * 一个内容类型可以有多个字段类型，改成一对多表。
     *
     * @ORM\OneToMany(targetEntity="FieldTypeEntity", mappedBy="contentTypeEntity",
     *     orphanRemoval=true, cascade={"persist"} )
     */
    private $fieldsTypeEntitys;

    /**
     * @ORM\OneToMany(targetEntity="ContentEntity", mappedBy="contentTypeEntity",
     *     orphanRemoval=true, cascade={"persist"} )
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $contentEntitys;

    /**
     * @ORM\OneToOne(targetEntity="ProductTypeEntity", mappedBy="contentTypeEntity")
     */
    private $productTypeEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="boolean",nullable=true, options={"default":0})
     */
    private $deleted;


    public function __construct()
    {
        $this->fieldsTypeEntitys = new ArrayCollection();
        $this->contentEntitys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentTypeName(): ?string
    {
        return $this->contentTypeName;
    }

    public function setContentTypeName(string $contentTypeName): self
    {
        $this->contentTypeName = $contentTypeName;

        return $this;
    }

    public function getContentTypeDescription(): ?string
    {
        return $this->contentTypeDescription;
    }

    public function setContentTypeDescription(?string $contentTypeDescription): self
    {
        $this->contentTypeDescription = $contentTypeDescription;

        return $this;
    }

    public function getContentTypeMachineAlias(): ?string
    {
        return $this->contentTypeMachineAlias;
    }

    public function setContentTypeMachineAlias(string $contentTypeMachineAlias): self
    {
        $this->contentTypeMachineAlias = $contentTypeMachineAlias;

        return $this;
    }

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(?int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection|FieldTypeEntity[]
     */
    public function getFieldsTypeEntitys(): Collection
    {
        return $this->fieldsTypeEntitys;
    }

    public function addFieldsTypeEntity(FieldTypeEntity $fieldsTypeEntity): self
    {
        if (!$this->fieldsTypeEntitys->contains($fieldsTypeEntity)) {
            $this->fieldsTypeEntitys[] = $fieldsTypeEntity;
            $fieldsTypeEntity->setContentTypeEntityId($this);
        }

        return $this;
    }

    public function removeFieldsTypeEntity(FieldTypeEntity $fieldsTypeEntity): self
    {
        if ($this->fieldsTypeEntitys->contains($fieldsTypeEntity)) {
            $this->fieldsTypeEntitys->removeElement($fieldsTypeEntity);
            // set the owning side to null (unless already changed)
            if ($fieldsTypeEntity->getContentTypeEntityId() === $this) {
                $fieldsTypeEntity->setContentTypeEntityId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ContentEntity[]
     */
    public function getContentEntitys(): Collection
    {
        return $this->contentEntitys;
    }

    public function addContentEntity(ContentEntity $contentEntity): self
    {
        if (!$this->contentEntitys->contains($contentEntity)) {
            $this->contentEntitys[] = $contentEntity;
            $contentEntity->setContentTypeEntity($this);
        }

        return $this;
    }

    public function removeContentEntity(ContentEntity $contentEntity): self
    {
        if ($this->contentEntitys->contains($contentEntity)) {
            $this->contentEntitys->removeElement($contentEntity);
            // set the owning side to null (unless already changed)
            if ($contentEntity->getContentTypeEntity() === $this) {
                $contentEntity->setContentTypeEntity(null);
            }
        }

        return $this;
    }

    public function getProductTypeEntity(): ?ProductTypeEntity
    {
        return $this->productTypeEntity;
    }

    public function setProductTypeEntity(?ProductTypeEntity $productTypeEntity): self
    {
        $this->productTypeEntity = $productTypeEntity;

        // set (or unset) the owning side of the relation if necessary
        $newContentTypeEntity = $productTypeEntity === null ? null : $this;
        if ($newContentTypeEntity !== $productTypeEntity->getContentTypeEntity()) {
            $productTypeEntity->setContentTypeEntity($newContentTypeEntity);
        }

        return $this;
    }

}
