<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * 用户表
 * @ORM\Entity(repositoryClass="App\Repository\UserEntityRepository")
 */
class UserEntity extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ContentEntity", mappedBy="author")
     */
    private $contentEntitys;

    /**
     * 一个用户可以有多个收货地址
     * @ORM\OneToMany(targetEntity="UserAddressEntity", mappedBy="userEntity",
     *     orphanRemoval=true, cascade={"persist"} ))
     */
    private $userAddressEntitys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentEntity", mappedBy="author")
     */
    private $commentEntities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CartEntity", mappedBy="buyer", orphanRemoval=true)
     */
    private $cartEntities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderEntity", mappedBy="buyer")
     */
    private $orderEntities;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserPermissionGroupEntity", inversedBy="adminUser")
     */
    private $userPermissionGroupEntity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FileManagedEntity", mappedBy="author")
     */
    private $fileManagedEntities;


    public function __construct()
    {
        parent::__construct();
        $this->contentEntitys = new ArrayCollection();
        $this->userAddressEntitys = new ArrayCollection();
        $this->commentEntities = new ArrayCollection();
        $this->cartEntities = new ArrayCollection();
        $this->orderEntities = new ArrayCollection();
        $this->fileManagedEntities = new ArrayCollection();
    }


    /**
     * @return Collection|ContentEntity[]
     */
    public function getContentEntitys(): Collection
    {
        return $this->contentEntitys;
    }

    public function addContentEntity(ContentEntity $contentEntity): self
    {
        if (!$this->contentEntitys->contains($contentEntity)) {
            $this->contentEntitys[] = $contentEntity;
            $contentEntity->setAuthor($this);
        }

        return $this;
    }

    public function removeContentEntity(ContentEntity $contentEntity): self
    {
        if ($this->contentEntitys->contains($contentEntity)) {
            $this->contentEntitys->removeElement($contentEntity);
            // set the owning side to null (unless already changed)
            if ($contentEntity->getAuthor() === $this) {
                $contentEntity->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserAddressEntity[]
     */
    public function getUserAddressEntitys(): Collection
    {
        return $this->userAddressEntitys;
    }

    public function addUserAddressEntity(UserAddressEntity $userAddressEntity): self
    {
        if (!$this->userAddressEntitys->contains($userAddressEntity)) {
            $this->userAddressEntitys[] = $userAddressEntity;
            $userAddressEntity->setUserEntity($this);
        }

        return $this;
    }

    public function removeUserAddressEntity(UserAddressEntity $userAddressEntity): self
    {
        if ($this->userAddressEntitys->contains($userAddressEntity)) {
            $this->userAddressEntitys->removeElement($userAddressEntity);
            // set the owning side to null (unless already changed)
            if ($userAddressEntity->getUserEntity() === $this) {
                $userAddressEntity->setUserEntity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentEntity[]
     */
    public function getCommentEntities(): Collection
    {
        return $this->commentEntities;
    }

    public function addCommentEntity(CommentEntity $commentEntity): self
    {
        if (!$this->commentEntities->contains($commentEntity)) {
            $this->commentEntities[] = $commentEntity;
            $commentEntity->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentEntity(CommentEntity $commentEntity): self
    {
        if ($this->commentEntities->contains($commentEntity)) {
            $this->commentEntities->removeElement($commentEntity);
            // set the owning side to null (unless already changed)
            if ($commentEntity->getAuthor() === $this) {
                $commentEntity->setAuthor(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatar(int $size): ?string
    {
        $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "&s=" . $size;

        return $grav_url;
    }

    /**
     * @return Collection|CartEntity[]
     */
    public function getCartEntities(): Collection
    {
        return $this->cartEntities;
    }

    public function addCartEntity(CartEntity $cartEntity): self
    {
        if (!$this->cartEntities->contains($cartEntity)) {
            $this->cartEntities[] = $cartEntity;
            $cartEntity->setBuyer($this);
        }

        return $this;
    }

    public function removeCartEntity(CartEntity $cartEntity): self
    {
        if ($this->cartEntities->contains($cartEntity)) {
            $this->cartEntities->removeElement($cartEntity);
            // set the owning side to null (unless already changed)
            if ($cartEntity->getBuyer() === $this) {
                $cartEntity->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderEntity[]
     */
    public function getOrderEntities(): Collection
    {
        return $this->orderEntities;
    }

    public function addOrderEntity(OrderEntity $orderEntity): self
    {
        if (!$this->orderEntities->contains($orderEntity)) {
            $this->orderEntities[] = $orderEntity;
            $orderEntity->setBuyer($this);
        }

        return $this;
    }

    public function removeOrderEntity(OrderEntity $orderEntity): self
    {
        if ($this->orderEntities->contains($orderEntity)) {
            $this->orderEntities->removeElement($orderEntity);
            // set the owning side to null (unless already changed)
            if ($orderEntity->getBuyer() === $this) {
                $orderEntity->setBuyer(null);
            }
        }

        return $this;
    }

    public function getUserPermissionGroupEntity(): ?UserPermissionGroupEntity
    {
        return $this->userPermissionGroupEntity;
    }

    public function setUserPermissionGroupEntity(?UserPermissionGroupEntity $userPermissionGroupEntity): self
    {
        $this->userPermissionGroupEntity = $userPermissionGroupEntity;

        return $this;
    }

    /**
     * @return Collection|FileManagedEntity[]
     */
    public function getFileManagedEntities(): Collection
    {
        return $this->fileManagedEntities;
    }

    public function addFileManagedEntity(FileManagedEntity $fileManagedEntity): self
    {
        if (!$this->fileManagedEntities->contains($fileManagedEntity)) {
            $this->fileManagedEntities[] = $fileManagedEntity;
            $fileManagedEntity->setAuthor($this);
        }

        return $this;
    }

    public function removeFileManagedEntity(FileManagedEntity $fileManagedEntity): self
    {
        if ($this->fileManagedEntities->contains($fileManagedEntity)) {
            $this->fileManagedEntities->removeElement($fileManagedEntity);
            // set the owning side to null (unless already changed)
            if ($fileManagedEntity->getAuthor() === $this) {
                $fileManagedEntity->setAuthor(null);
            }
        }

        return $this;
    }

}
