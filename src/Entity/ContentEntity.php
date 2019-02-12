<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 各种内容类型的实体类，用于在表中存储各种类型的内容。
 * @ORM\Entity(repositoryClass="App\Repository\ContentEntityRepository")
 */
class ContentEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $id;

    /**
     * 内容标题
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * 内容BODY
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * 发布日期
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * 修改日期
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changeAt;

    /**
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="contentEntitys")
     * @ORM\JoinColumn(name="user_id",nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="ContentTypeEntity", inversedBy="contentEntitys")
     * @ORM\JoinColumn(name="content_type_id",nullable=false)
     */
    private $contentTypeEntity;

    /**
     * @ORM\OneToMany(targetEntity="FieldTableEntity", mappedBy="contentEntity")
     */
    private $fieldTableEntitys;

    /**
     * @ORM\OneToMany(targetEntity="FieldTextTableEntity", mappedBy="contentEntity")
     */
    private $fieldTextTableEntitys;

    /**
     * @ORM\OneToMany(targetEntity="FieldFileTableEntity", mappedBy="contentEntity")
     */
    private $fieldFileTableEntitys;

    /**
     * @ORM\OneToMany(targetEntity="FieldImageTableEntity", mappedBy="contentEntity")
     */
    private $fieldImageTableEntitys;

    /**
     * @ORM\OneToMany(targetEntity="FieldContentTableEntity", mappedBy="contentEntity")
     */
    private $fieldContentTableEntitys;

    /**
     * @ORM\OneToMany(targetEntity="FieldTaxonomyTableEntity", mappedBy="contentEntity")
     */
    private $fieldTaxonomyTableEntitys;

    /**
     * 销售属性字段,以json类型存储在表中
     * @ORM\OneToOne(targetEntity="FieldProductPropsTableEntity",mappedBy ="contentEntity")
     */
    private $fieldProductPropsTableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentEntity", mappedBy="contentEntity")
     */
    private $commentEntities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartEntity", mappedBy="productContentEntity")
     */
    private $cartEntities;

    public function __construct()
    {
        $this->fieldTableEntitys = new ArrayCollection();
        $this->fieldFileTableEntitys = new ArrayCollection();
        $this->fieldImageTableEntitys = new ArrayCollection();
        $this->fieldTextTableEntitys = new ArrayCollection();
        $this->fieldTaxonomyTableEntitys = new ArrayCollection();
        $this->fieldContentTableEntitys = new ArrayCollection();
        $this->commentEntities = new ArrayCollection();
        $this->cartEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    

    public function getAuthor(): ?UserEntity
    {
        return $this->author;
    }

    public function setAuthor(?UserEntity $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContentTypeEntity(): ?ContentTypeEntity
    {
        return $this->contentTypeEntity;
    }

    public function setContentTypeEntity(?ContentTypeEntity $contentTypeEntity): self
    {
        $this->contentTypeEntity = $contentTypeEntity;

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

    public function getChangeAt(): ?\DateTimeInterface
    {
        return $this->changeAt;
    }

    public function setChangeAt(\DateTimeInterface $changeAt): self
    {
        $this->changeAt = $changeAt;

        return $this;
    }

    /**
     * @return Collection|FieldTableEntity[]
     */
    public function getFieldTableEntitys(): Collection
    {
        return $this->fieldTableEntitys;
    }

    public function addFieldTableEntity(FieldTableEntity $fieldTableEntity): self
    {
        if (!$this->fieldTableEntitys->contains($fieldTableEntity)) {
            $this->fieldTableEntitys[] = $fieldTableEntity;
            $fieldTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldTableEntity(FieldTableEntity $fieldTableEntity): self
    {
        if ($this->fieldTableEntitys->contains($fieldTableEntity)) {
            $this->fieldTableEntitys->removeElement($fieldTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldTableEntity->getContentEntity() === $this) {
                $fieldTableEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FieldFileTableEntity[]
     */
    public function getFieldFileTableEntitys(): Collection
    {
        return $this->fieldFileTableEntitys;
    }

    public function addFieldFileTableEntity(FieldFileTableEntity $fieldFileTableEntity): self
    {
        if (!$this->fieldFileTableEntitys->contains($fieldFileTableEntity)) {
            $this->fieldFileTableEntitys[] = $fieldFileTableEntity;
            $fieldFileTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldFileTableEntity(FieldFileTableEntity $fieldFileTableEntity): self
    {
        if ($this->fieldFileTableEntitys->contains($fieldFileTableEntity)) {
            $this->fieldFileTableEntitys->removeElement($fieldFileTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldFileTableEntity->getContentEntity() === $this) {
                $fieldFileTableEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FieldImageTableEntity[]
     */
    public function getFieldImageTableEntitys(): Collection
    {
        return $this->fieldImageTableEntitys;
    }

    public function addFieldImageTableEntity(FieldImageTableEntity $fieldImageTableEntity): self
    {
        if (!$this->fieldImageTableEntitys->contains($fieldImageTableEntity)) {
            $this->fieldImageTableEntitys[] = $fieldImageTableEntity;
            $fieldImageTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldImageTableEntity(FieldImageTableEntity $fieldImageTableEntity): self
    {
        if ($this->fieldImageTableEntitys->contains($fieldImageTableEntity)) {
            $this->fieldImageTableEntitys->removeElement($fieldImageTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldImageTableEntity->getContentEntity() === $this) {
                $fieldImageTableEntity->setContentEntity(null);
            }
        }

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
     * @return Collection|FieldTextTableEntity[]
     */
    public function getFieldTextTableEntitys(): Collection
    {
        return $this->fieldTextTableEntitys;
    }

    public function addFieldTextTableEntity(FieldTextTableEntity $fieldTextTableEntity): self
    {
        if (!$this->fieldTextTableEntitys->contains($fieldTextTableEntity)) {
            $this->fieldTextTableEntitys[] = $fieldTextTableEntity;
            $fieldTextTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldTextTableEntity(FieldTextTableEntity $fieldTextTableEntity): self
    {
        if ($this->fieldTextTableEntitys->contains($fieldTextTableEntity)) {
            $this->fieldTextTableEntitys->removeElement($fieldTextTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldTextTableEntity->getContentEntity() === $this) {
                $fieldTextTableEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FieldTaxonomyTableEntity[]
     */
    public function getFieldTaxonomyTableEntitys(): Collection
    {
        return $this->fieldTaxonomyTableEntitys;
    }

    public function addFieldTaxonomyTableEntity(FieldTaxonomyTableEntity $fieldTaxonomyTableEntity): self
    {
        if (!$this->fieldTaxonomyTableEntitys->contains($fieldTaxonomyTableEntity)) {
            $this->fieldTaxonomyTableEntitys[] = $fieldTaxonomyTableEntity;
            $fieldTaxonomyTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldTaxonomyTableEntity(FieldTaxonomyTableEntity $fieldTaxonomyTableEntity): self
    {
        if ($this->fieldTaxonomyTableEntitys->contains($fieldTaxonomyTableEntity)) {
            $this->fieldTaxonomyTableEntitys->removeElement($fieldTaxonomyTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldTaxonomyTableEntity->getContentEntity() === $this) {
                $fieldTaxonomyTableEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FieldTaxonomyTableEntity[]
     */
    public function getFieldContentTableEntitys(): Collection
    {
        return $this->fieldContentTableEntitys;
    }

    public function addFieldContentTableEntity(FieldTaxonomyTableEntity $fieldContentTableEntity): self
    {
        if (!$this->fieldContentTableEntitys->contains($fieldContentTableEntity)) {
            $this->fieldContentTableEntitys[] = $fieldContentTableEntity;
            $fieldContentTableEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeFieldContentTableEntity(FieldTaxonomyTableEntity $fieldContentTableEntity): self
    {
        if ($this->fieldContentTableEntitys->contains($fieldContentTableEntity)) {
            $this->fieldContentTableEntitys->removeElement($fieldContentTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldContentTableEntity->getContentEntity() === $this) {
                $fieldContentTableEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    public function getFieldProductPropsTableEntity(): ?FieldProductPropsTableEntity
    {
        return $this->fieldProductPropsTableEntity;
    }

    public function setFieldProductPropsTableEntity(?FieldProductPropsTableEntity $fieldProductPropsTableEntity): self
    {
        $this->fieldProductPropsTableEntity = $fieldProductPropsTableEntity;

        // set (or unset) the owning side of the relation if necessary
        $newContentEntity = $fieldProductPropsTableEntity === null ? null : $this;
        if ($newContentEntity !== $fieldProductPropsTableEntity->getContentEntity()) {
            $fieldProductPropsTableEntity->setContentEntity($newContentEntity);
        }

        return $this;
    }

    /**
     * @return Collection|CommentEntity[]
     */
    public function getCommentEntities(): Collection
    {
        return $this->commentEntities;
    }

    public function addCommentEntity(CommentEntity $commentEntity): self
    {
        if (!$this->commentEntities->contains($commentEntity)) {
            $this->commentEntities[] = $commentEntity;
            $commentEntity->setContentEntity($this);
        }

        return $this;
    }

    public function removeCommentEntity(CommentEntity $commentEntity): self
    {
        if ($this->commentEntities->contains($commentEntity)) {
            $this->commentEntities->removeElement($commentEntity);
            // set the owning side to null (unless already changed)
            if ($commentEntity->getContentEntity() === $this) {
                $commentEntity->setContentEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CartEntity[]
     */
    public function getCartEntities(): Collection
    {
        return $this->cartEntities;
    }

    public function addCartEntity(CartEntity $cartEntity): self
    {
        if (!$this->cartEntities->contains($cartEntity)) {
            $this->cartEntities[] = $cartEntity;
            $cartEntity->setProductContentEntity($this);
        }

        return $this;
    }

    public function removeCartEntity(CartEntity $cartEntity): self
    {
        if ($this->cartEntities->contains($cartEntity)) {
            $this->cartEntities->removeElement($cartEntity);
            // set the owning side to null (unless already changed)
            if ($cartEntity->getProductContentEntity() === $this) {
                $cartEntity->setProductContentEntity(null);
            }
        }

        return $this;
    }

}
