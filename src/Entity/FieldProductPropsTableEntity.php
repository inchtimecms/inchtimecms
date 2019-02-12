<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FieldProductPropsTableEntityRepository")
 */
class FieldProductPropsTableEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="ContentEntity", inversedBy="fieldProductPropsTableEntity")
     * @ORM\JoinColumn(name="content_id", nullable=false)
     */
    private $contentEntity;


    /**
     * 商品销售属性的json串的值
     * @ORM\Column(type="json")
     */
    private $fieldProductPropsValue;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $deleted;

    /**
     * @ORM\Column(type="json")
     */
    private $group1PropsJson = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $group2PropsJson = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldProductPropsValue()
    {
        return $this->fieldProductPropsValue;
    }

    public function setFieldProductPropsValue($fieldProductPropsValue): self
    {
        $this->fieldProductPropsValue = $fieldProductPropsValue;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getContentEntity(): ?ContentEntity
    {
        return $this->contentEntity;
    }

    public function setContentEntity(ContentEntity $contentEntity): self
    {
        $this->contentEntity = $contentEntity;

        return $this;
    }

    public function getGroup1PropsJson(): ?array
    {
        return $this->group1PropsJson;
    }

    public function setGroup1PropsJson(array $group1PropsJson): self
    {
        $this->group1PropsJson = $group1PropsJson;

        return $this;
    }

    public function getGroup2PropsJson(): ?array
    {
        return $this->group2PropsJson;
    }

    public function setGroup2PropsJson(?array $group2PropsJson): self
    {
        $this->group2PropsJson = $group2PropsJson;

        return $this;
    }
}
