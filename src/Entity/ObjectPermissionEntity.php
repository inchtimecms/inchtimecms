<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectPermissionEntityRepository")
 */
class ObjectPermissionEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $permissionJson = [];


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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
