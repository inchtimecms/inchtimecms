<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuItemEntityRepository")
 */
class MenuItemEntity
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
    private $itemName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $itemUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemRate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuItemEntity", inversedBy="childItemEntitys")
     */
    private $parentItem;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuItemEntity", mappedBy="parentItem")
     */
    private $childItemEntitys;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuEntity", inversedBy="MenuItemEntitys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $menuEntity;


    public function __construct()
    {
        $this->childItemEntitys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getItemUrl(): ?string
    {
        return $this->itemUrl;
    }

    public function setItemUrl(string $itemUrl): self
    {
        $this->itemUrl = $itemUrl;

        return $this;
    }

    public function getItemRate(): ?int
    {
        return $this->itemRate;
    }

    public function setItemRate(int $itemRate): self
    {
        $this->itemRate = $itemRate;

        return $this;
    }

    public function getParentItem(): ?self
    {
        return $this->parentItem;
    }

    public function setParentItem(?self $parentItem): self
    {
        $this->parentItem = $parentItem;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildItemEntitys(): Collection
    {
        return $this->childItemEntitys;
    }

    public function addChildItemEntity(self $childItemEntity): self
    {
        if (!$this->childItemEntitys->contains($childItemEntity)) {
            $this->childItemEntitys[] = $childItemEntity;
            $childItemEntity->setParentItem($this);
        }

        return $this;
    }

    public function removeChildItemEntity(self $childItemEntity): self
    {
        if ($this->childItemEntitys->contains($childItemEntity)) {
            $this->childItemEntitys->removeElement($childItemEntity);
            // set the owning side to null (unless already changed)
            if ($childItemEntity->getParentItem() === $this) {
                $childItemEntity->setParentItem(null);
            }
        }

        return $this;
    }

    public function getMenuEntity(): ?MenuEntity
    {
        return $this->menuEntity;
    }

    public function setMenuEntity(?MenuEntity $menuEntity): self
    {
        $this->menuEntity = $menuEntity;

        return $this;
    }


}
