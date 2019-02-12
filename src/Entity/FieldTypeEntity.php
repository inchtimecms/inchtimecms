<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * 字段类型entity,用于内容类型添加的字段类型的类。根据此类型动态新建字段表。
 * @ORM\Entity(repositoryClass="App\Repository\FieldTypeEntityRepository")
 */
class FieldTypeEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fieldName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $fieldMachineAlias;

    /**
     * 字段类型的描述。
     * @ORM\Column(type="string", length=255)
     */
    private $fieldDescription;

    /**
     * @var ContentTypeEntity
     *
     * @ORM\ManyToOne(targetEntity="ContentTypeEntity", inversedBy="fieldsTypeEntitys")
     * @ORM\JoinColumn(name="content_type_id", nullable=false)
     */
    private $contentTypeEntity;

    /**
     * @var FieldTypeValueEntity
     *
     * @ORM\ManyToOne(targetEntity="FieldTypeValueEntity", inversedBy="fieldTypeEntitys")
     * @ORM\JoinColumn(name="field_type_value_id", nullable=false)
     */
    private $fieldTypeValue;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fieldSettings;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    public function getFieldMachineAlias(): ?string
    {
        return $this->fieldMachineAlias;
    }

    public function setFieldMachineAlias(string $fieldMachineAlias): self
    {
        $this->fieldMachineAlias = $fieldMachineAlias;

        return $this;
    }

    public function getFieldDescription(): ?string
    {
        return $this->fieldDescription;
    }

    public function setFieldDescription(string $fieldDescription): self
    {
        $this->fieldDescription = $fieldDescription;

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

    public function getFieldSettings(): ?string
    {
        return $this->fieldSettings;
    }

    public function setFieldSettings(?string $fieldSettings): self
    {
        $this->fieldSettings = $fieldSettings;

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

    public function getFieldTypeValue(): ?FieldTypeValueEntity
    {
        return $this->fieldTypeValue;
    }

    public function setFieldTypeValue(?FieldTypeValueEntity $fieldTypeValue): self
    {
        $this->fieldTypeValue = $fieldTypeValue;

        return $this;
    }


}
