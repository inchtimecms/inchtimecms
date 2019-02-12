<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * 常用类型字段表Entity,$fieldTableValue在表中的类型根据字段类型而有所不同。
 * 在创建内容类型时，除字段和图像类型外的字段 动态创建表
 * @ORM\Entity(repositoryClass="App\Repository\FieldTableEntityRepository")
 */
class FieldTableEntity
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
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldTableEntitys")
     * @ORM\JoinColumn(name="content_id", nullable=false)
     */
    private $contentEntity;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    /**
     * 字段表的值列,都存成string类型吧，读取到后再转
     * @ORM\Column(type="string",nullable=true)
     */
    private $fieldTableValue;

    /**
     * fieldTableValue 在数据库中统一使用string存储字段值，但是使用中要转换成对应的类型，此字段代表fieldTableValue的类型
     * @ORM\Column(type="string",nullable=true)
     */
    private $fieldTableValueType;

    /**
     * 存储当前字段的别名，此别名和内容类型的字段别名一一对应
     * @ORM\Column(name="field_alias", type="string", nullable=true)
     */
    private $fieldAliasInContentTypeEntity;

    public function getId(): ?int
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


}
