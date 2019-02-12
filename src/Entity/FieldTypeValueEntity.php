<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * 字段类型FieldTypeEntity 在数据库中对应的类型值的entity。
 * 在此entity中定义一些常用的值，用于在内容类型创建字段时选择字段对应的数据库类型
 * @ORM\Entity(repositoryClass="App\Repository\FieldTypeValueEntityRepository")
 */
class FieldTypeValueEntity
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
    private $fieldTypeValueType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fieldValueTypeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fieldTypeInSQL;

    /**
     * @var FieldTypeEntity
     *
     * @ORM\OneToMany(targetEntity="FieldTypeEntity", mappedBy="fieldTypeValue",
     *     orphanRemoval=true, cascade={"persist"} )
     */
    private $fieldTypeEntitys;

    public function __construct()
    {
        $this->fieldTypeEntitys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldTypeValueType(): ?string
    {
        return $this->fieldTypeValueType;
    }

    public function setFieldTypeValueType(string $fieldTypeValueType): self
    {
        $this->fieldTypeValueType = $fieldTypeValueType;

        return $this;
    }

    public function getFieldValueTypeName(): ?string
    {
        return $this->fieldValueTypeName;
    }

    public function setFieldValueTypeName(string $fieldValueTypeName): self
    {
        $this->fieldValueTypeName = $fieldValueTypeName;

        return $this;
    }

    public function getFieldTypeInSQL(): ?string
    {
        return $this->fieldTypeInSQL;
    }

    public function setFieldTypeInSQL(string $fieldTypeInSQL): self
    {
        $this->fieldTypeInSQL = $fieldTypeInSQL;

        return $this;
    }

    /**
     * @return Collection|FieldTypeEntity[]
     */
    public function getFieldTypeEntitys(): Collection
    {
        return $this->fieldTypeEntitys;
    }

    public function addFieldTypeEntity(FieldTypeEntity $fieldTypeEntity): self
    {
        if (!$this->fieldTypeEntitys->contains($fieldTypeEntity)) {
            $this->fieldTypeEntitys[] = $fieldTypeEntity;
            $fieldTypeEntity->setFieldTypeValue($this);
        }

        return $this;
    }

    public function removeFieldTypeEntity(FieldTypeEntity $fieldTypeEntity): self
    {
        if ($this->fieldTypeEntitys->contains($fieldTypeEntity)) {
            $this->fieldTypeEntitys->removeElement($fieldTypeEntity);
            // set the owning side to null (unless already changed)
            if ($fieldTypeEntity->getFieldTypeValue() === $this) {
                $fieldTypeEntity->setFieldTypeValue(null);
            }
        }

        return $this;
    }


}
