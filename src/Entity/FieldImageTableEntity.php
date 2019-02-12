<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * 图像类型字段 动态添加表
 * @ORM\Entity(repositoryClass="App\Repository\FieldImageTableEntityRepository")
 */
class FieldImageTableEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ContentTypeEntity")
     * @ORM\JoinColumn(name="content_type_id", nullable=false)
     */
    private $contentTypeEntity;

    /**
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldImageTableEntitys")
     * @ORM\JoinColumn(name="content_id", nullable=false)
     */
    private $contentEntity;

    /**
     * @ORM\OneToOne(targetEntity="FileManagedEntity", inversedBy="fieldImageTableEntity")
     * @ORM\JoinColumn(name="file_managed_id", nullable=false)
     */
    private $fileManagedEntity;

    /**
     * 存储当前字段的别名，此别名和内容类型的字段别名一一对应
     * @ORM\Column(name="field_alias", type="string", nullable=true)
     */
    private $fieldAliasInContentTypeEntity;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageAlt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageTitle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imageWidth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imageHeight;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageAlt(): ?string
    {
        return $this->imageAlt;
    }

    public function setImageAlt(?string $imageAlt): self
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    public function getImageTitle(): ?string
    {
        return $this->imageTitle;
    }

    public function setImageTitle(?string $imageTitle): self
    {
        $this->imageTitle = $imageTitle;

        return $this;
    }

    public function getImageWidth(): ?int
    {
        return $this->imageWidth;
    }

    public function setImageWidth(?int $imageWidth): self
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    public function getImageHeight(): ?int
    {
        return $this->imageHeight;
    }

    public function setImageHeight(?int $imageHeight): self
    {
        $this->imageHeight = $imageHeight;

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

    public function getContentTypeEntity(): ?ContentTypeEntity
    {
        return $this->contentTypeEntity;
    }

    public function setContentTypeEntity(?ContentTypeEntity $contentTypeEntity): self
    {
        $this->contentTypeEntity = $contentTypeEntity;

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

    public function getFileManagedEntity(): ?FileManagedEntity
    {
        return $this->fileManagedEntity;
    }

    public function setFileManagedEntity(FileManagedEntity $fileManagedEntity): self
    {
        $this->fileManagedEntity = $fileManagedEntity;

        return $this;
    }

    public function getFieldAliasInContentTypeEntity(): ?string
    {
        return $this->fieldAliasInContentTypeEntity;
    }

    public function setFieldAliasInContentTypeEntity(?string $fieldAliasInContentTypeEntity): self
    {
        $this->fieldAliasInContentTypeEntity = $fieldAliasInContentTypeEntity;

        return $this;
    }

}
