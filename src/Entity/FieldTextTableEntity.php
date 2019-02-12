<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 长文本类型的字段单独放一个表
 * @ORM\Entity(repositoryClass="App\Repository\FieldTextTableEntityRepository")
 */
class FieldTextTableEntity
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
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldTextTableEntitys")
     * @ORM\JoinColumn(name="content_id", nullable=false)
     */
    private $contentEntity;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    /**
     * 字段表长文本类型存到这个表中
     * @ORM\Column(type="text",nullable=true)
     */
    private $fieldTableValue;

    /**
     * fieldTableValue 在数据库中统一使用string存储字段值
     * @ORM\Column(type="string",nullable=true)
     */
    private $fieldTableValueType;

    /**
     * 存储当前字段的别名，此别名和内容类型的字段别名一一对应
     * @ORM\Column(name="field_alias", type="string", nullable=true)
     */
    private $fieldAliasInContentTypeEntity;

    public function getId()
    {
        return $this->id;
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

    public function getFieldTableValue(): ?string
    {
        return $this->fieldTableValue;
    }

    public function setFieldTableValue(?string $fieldTableValue): self
    {
        $this->fieldTableValue = $fieldTableValue;

        return $this;
    }

    public function getFieldTableValueType(): ?string
    {
        return $this->fieldTableValueType;
    }

    public function setFieldTableValueType(?string $fieldTableValueType): self
    {
        $this->fieldTableValueType = $fieldTableValueType;

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
}
