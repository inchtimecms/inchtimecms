<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPermissionGroupEntityRepository")
 */
class UserPermissionGroupEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserEntity", mappedBy="userPermissionGroupEntity")
     */
    private $adminUser;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $groupName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $groupAlias;

    /**
     * @ORM\Column(type="json")
     */
    private $permissionJson = [];


    public function __construct()
    {
        $this->adminUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|UserEntity[]
     */
    public function getAdminUser(): Collection
    {
        return $this->adminUser;
    }

    public function addAdminUser(UserEntity $adminUser): self
    {
        if (!$this->adminUser->contains($adminUser)) {
            $this->adminUser[] = $adminUser;
            $adminUser->setUserPermissionGroupEntity($this);
        }

        return $this;
    }

    public function removeAdminUser(UserEntity $adminUser): self
    {
        if ($this->adminUser->contains($adminUser)) {
            $this->adminUser->removeElement($adminUser);
            // set the owning side to null (unless already changed)
            if ($adminUser->getUserPermissionGroupEntity() === $this) {
                $adminUser->setUserPermissionGroupEntity(null);
            }
        }

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getGroupAlias(): ?string
    {
        return $this->groupAlias;
    }

    public function setGroupAlias(string $groupAlias): self
    {
        $this->groupAlias = $groupAlias;

        return $this;
    }

    public function getPermissionJson(): ?array
    {
        return $this->permissionJson;
    }

    public function setPermissionJson(array $permissionJson): self
    {
        $this->permissionJson = $permissionJson;

        return $this;
    }

}
