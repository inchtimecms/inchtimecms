<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuEntityRepository")
 */
class MenuEntity
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
    private $menuName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $menuAlias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuItemEntity", mappedBy="menuEntity")
     * @ORM\OrderBy({"itemRate" = "ASC"})
     */
    private $MenuItemEntitys;

    public function __construct()
    {
        $this->MenuItemEntitys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenuName(): ?string
    {
        return $this->menuName;
    }

    public function setMenuName(string $menuName): self
    {
        $this->menuName = $menuName;

        return $this;
    }

    public function getMenuAlias(): ?string
    {
        return $this->menuAlias;
    }

    public function setMenuAlias(string $menuAlias): self
    {
        $this->menuAlias = $menuAlias;

        return $this;
    }

    /**
     * @return Collection|MenuItemEntity[]
     */
    public function getMenuItemEntitys(): Collection
    {
        return $this->MenuItemEntitys;
    }

    public function addMenuItemEntity(MenuItemEntity $menuItemEntity): self
    {
        if (!$this->MenuItemEntitys->contains($menuItemEntity)) {
            $this->MenuItemEntitys[] = $menuItemEntity;
            $menuItemEntity->setMenuEntity($this);
        }

        return $this;
    }

    public function removeMenuItemEntity(MenuItemEntity $menuItemEntity): self
    {
        if ($this->MenuItemEntitys->contains($menuItemEntity)) {
            $this->MenuItemEntitys->removeElement($menuItemEntity);
            // set the owning side to null (unless already changed)
            if ($menuItemEntity->getMenuEntity() === $this) {
                $menuItemEntity->setMenuEntity(null);
            }
        }

        return $this;
    }
}
