<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * 分类标签Entity
 * @ORM\Entity(repositoryClass="App\Repository\TaxonomyEntityRepository")
 */
class TaxonomyEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TaxonomyTypeEntity", inversedBy="taxonomyEntitys")
     * @ORM\JoinColumn(name="taxonomy_type_id")
     */
    private $taxonomyTypeEntity;

    /**
     * @ORM\OneToMany(targetEntity="FieldTaxonomyTableEntity", mappedBy="taxonomyEntity")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $fieldTaxonomyTableEntitys;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    private $taxonomyWord;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taxonomyDesc;

    /**
     * @ORM\Column(type="integer",options={"unsigned":true, "default":0})
     */
    private $weight;
    /**
     * @ORM\Column(type="datetime")
     */
    private $changedAt;

    public function __construct()
    {
        $this->fieldTaxonomyTableEntitys = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTaxonomyWord(): ?string
    {
        return $this->taxonomyWord;
    }

    public function setTaxonomyWord(string $taxonomyWord): self
    {
        $this->taxonomyWord = $taxonomyWord;

        return $this;
    }

    public function getTaxonomyDesc(): ?string
    {
        return $this->taxonomyDesc;
    }

    public function setTaxonomyDesc(?string $taxonomyDesc): self
    {
        $this->taxonomyDesc = $taxonomyDesc;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeInterface $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function getTaxonomyTypeEntity(): ?TaxonomyTypeEntity
    {
        return $this->taxonomyTypeEntity;
    }

    public function setTaxonomyTypeEntity(?TaxonomyTypeEntity $taxonomyTypeEntity): self
    {
        $this->taxonomyTypeEntity = $taxonomyTypeEntity;

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
            $fieldTaxonomyTableEntity->setTaxonomyEntity($this);
        }

        return $this;
    }

    public function removeFieldTaxonomyTableEntity(FieldTaxonomyTableEntity $fieldTaxonomyTableEntity): self
    {
        if ($this->fieldTaxonomyTableEntitys->contains($fieldTaxonomyTableEntity)) {
            $this->fieldTaxonomyTableEntitys->removeElement($fieldTaxonomyTableEntity);
            // set the owning side to null (unless already changed)
            if ($fieldTaxonomyTableEntity->getTaxonomyEntity() === $this) {
                $fieldTaxonomyTableEntity->setTaxonomyEntity(null);
            }
        }

        return $this;
    }
}
