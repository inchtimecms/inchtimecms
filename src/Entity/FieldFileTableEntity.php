<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * 根据文件类型字段创建表
 * @ORM\Entity(repositoryClass="App\Repository\FieldFileTableEntityRepository")
 */
class FieldFileTableEntity
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
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldFileTableEntitys")
     * @ORM\JoinColumn(name="content_id", nullable=false)
     */
    private $contentEntity;

    /**
     * @ORM\OneToOne(targetEntity="FileManagedEntity", inversedBy="fieldFileTableEntity")
     * @ORM\JoinColumn(name="file_managed_id", nullable=false)
     */
    private $fileManagedEntity;


    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $fileDescription;

    /**
     * 存储当前字段的别名，此别名和内容类型的字段别名一一对应
     * @ORM\Column(name="field_alias", type="string", nullable=true)
     */
    private $fieldAliasInContentTypeEntity;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileDescription(): ?string
    {
        return $this->fileDescription;
    }

    public function setFileDescription(?string $fileDescription): self
    {
        $this->fileDescription = $fileDescription;

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
