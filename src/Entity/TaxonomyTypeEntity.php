<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * 分类标签类型Entity
 * @ORM\Entity(repositoryClass="App\Repository\TaxonomyTypeEntityRepository")
 */
class TaxonomyTypeEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $taxonomyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $taxonomyDesc;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $taxonomyAlias;

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyEntity", mappedBy="taxonomyTypeEntity")
     */
    private $taxonomyEntitys;

    public function __construct()
    {
        $this->taxonomyEntitys = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTaxonomyName(): ?string
    {
        return $this->taxonomyName;
    }

    public function setTaxonomyName(string $taxonomyName): self
    {
        $this->taxonomyName = $taxonomyName;

        return $this;
    }

    public function getTaxonomyDesc(): ?string
    {
        return $this->taxonomyDesc;
    }

    public function setTaxonomyDesc(string $taxonomyDesc): self
    {
        $this->taxonomyDesc = $taxonomyDesc;

        return $this;
    }

    /**
     * @return Collection|TaxonomyEntity[]
     */
    public function getTaxonomyEntitys(): Collection
    {
        return $this->taxonomyEntitys;
    }

    public function addTaxonomyEntity(TaxonomyEntity $taxonomyEntity): self
    {
        if (!$this->taxonomyEntitys->contains($taxonomyEntity)) {
            $this->taxonomyEntitys[] = $taxonomyEntity;
            $taxonomyEntity->setTaxonomyTypeEntity($this);
        }

        return $this;
    }

    public function removeTaxonomyEntity(TaxonomyEntity $taxonomyEntity): self
    {
        if ($this->taxonomyEntitys->contains($taxonomyEntity)) {
            $this->taxonomyEntitys->removeElement($taxonomyEntity);
            // set the owning side to null (unless already changed)
            if ($taxonomyEntity->getTaxonomyTypeEntity() === $this) {
                $taxonomyEntity->setTaxonomyTypeEntity(null);
            }
        }

        return $this;
    }

    public function getTaxonomyAlias(): ?string
    {
        return $this->taxonomyAlias;
    }

    public function setTaxonomyAlias(string $taxonomyAlias): self
    {
        $this->taxonomyAlias = $taxonomyAlias;

        return $this;
    }
}
