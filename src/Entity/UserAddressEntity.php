<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * 用户地址entity,用于收货
 * @ORM\Entity(repositoryClass="App\Repository\UserAddressEntityRepository")
 */
class UserAddressEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 收货人姓名
     * @ORM\Column(type="string",length=20)
     */
    private $consigneeName;

    /**
     * 收货人电话
     * @ORM\Column(type="string",length=12)
     */
    private $consigeneePhone;

    /**
     * 收货地址 省份
     * @ORM\Column(type="string")
     */
    private $province;

    /**
     * 收货地址 城市
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * 收货地址 县区
     * @ORM\Column(type="string")
     */
    private $district;

    /**
     * 收货地址 详细地址
     * @ORM\Column(type="string")
     */
    private $addressInfo;

    /**
     * 收货地址 邮编
     * @ORM\Column(type="string", length=20)
     */
    private $zipcode;

    /**
     * 一个用户可以有多个收货地址,用户的外键字段
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="userAddressEntitys")
     * @ORM\JoinColumn(name="user_id",nullable=true)
     */
    private $userEntity;

    /**
     * 是否是默认地址，如果为0则不是，如果为1则是
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $boolDefault;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderEntity", mappedBy="userAddressEntity")
     */
    private $orderEntities;

    public function __construct()
    {
        $this->orderEntities = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getConsigneeName(): ?string
    {
        return $this->consigneeName;
    }

    public function setConsigneeName(string $consigneeName): self
    {
        $this->consigneeName = $consigneeName;

        return $this;
    }

    public function getConsigeneePhone(): ?string
    {
        return $this->consigeneePhone;
    }

    public function setConsigeneePhone(string $consigeneePhone): self
    {
        $this->consigeneePhone = $consigeneePhone;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getAddressInfo(): ?string
    {
        return $this->addressInfo;
    }

    public function setAddressInfo(string $addressInfo): self
    {
        $this->addressInfo = $addressInfo;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getUserEntity(): ?UserEntity
    {
        return $this->userEntity;
    }

    public function setUserEntity(?UserEntity $userEntity): self
    {
        $this->userEntity = $userEntity;

        return $this;
    }

    public function getBoolDefault(): ?bool
    {
        return $this->boolDefault;
    }

    public function setBoolDefault(bool $boolDefault): self
    {
        $this->boolDefault = $boolDefault;

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
            $orderEntity->setUserAddressEntity($this);
        }

        return $this;
    }

    public function removeOrderEntity(OrderEntity $orderEntity): self
    {
        if ($this->orderEntities->contains($orderEntity)) {
            $this->orderEntities->removeElement($orderEntity);
            // set the owning side to null (unless already changed)
            if ($orderEntity->getUserAddressEntity() === $this) {
                $orderEntity->setUserAddressEntity(null);
            }
        }

        return $this;
    }
}
