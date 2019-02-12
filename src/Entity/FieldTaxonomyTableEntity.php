<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 内容实体ContentEntity 与 分类标签实体 TaxonomyEntity的关联表。
 * @ORM\Entity(repositoryClass="App\Repository\FieldTaxonomyTableEntityRepository")
 */
class FieldTaxonomyTableEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ContentEntity", inversedBy="fieldTaxonomyTableEntitys")
     * @ORM\JoinColumn(name="content_entity_id")
     */
    private $contentEntity;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyEntity", inversedBy="fieldTaxonomyTableEntitys")
     * @ORM\JoinColumn(name="taxonomy_entity_id")
     */
    private $taxonomyEntity;

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

    public function getTaxonomyEntity(): ?TaxonomyEntity
    {
        return $this->taxonomyEntity;
    }

    public function setTaxonomyEntity(?TaxonomyEntity $taxonomyEntity): self
    {
        $this->taxonomyEntity = $taxonomyEntity;

        return $this;
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
}
