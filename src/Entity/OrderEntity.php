<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderEntityRepository")
 */
class OrderEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 订单号
     * @ORM\Column(type="string",unique=true,nullable=false)
     */
    private $orderId;

    /**
     * 订单生成时间
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * 订单付款时间
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $payAt;

    /**
     * 订单超时时间
     * @ORM\Column(type="datetime")
     */
    private $expireAt;

    /**
     * 订单对应的购物车的item
     * @ORM\OneToMany(targetEntity="App\Entity\CartEntity", mappedBy="orderEntity")
     */
    private $cartEntitys;

    /**
     * 买家
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity", inversedBy="orderEntities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * 订单总价格
     * @ORM\Column(type="float")
     */
    private $orderTotalPrice;

    /**
     * 最终成交价
     * @ORM\Column(type="float")
     */
    private $finalPrice;

    /**
     * 订单状态, 未付款 0， 已付款 1，未收货 2，未评价 3，已完成 4，订单超时 5.
     * @ORM\Column(type="smallint",options={"unsigned":true, "default":0})
     */
    private $orderStatus;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserAddressEntity", inversedBy="orderEntities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userAddressEntity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PayMethodEntity")
     * @ORM\JoinColumn(nullable=true)
     */
    private $payMethod;

    public function __construct()
    {
        $this->cartEntitys = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
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

    public function getPayAt(): ?\DateTimeInterface
    {
        return $this->payAt;
    }

    public function setPayAt(\DateTimeInterface $payAt): self
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeInterface $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * @return Collection|CartEntity[]
     */
    public function getCartEntitys(): Collection
    {
        return $this->cartEntitys;
    }

    public function addCartEntity(CartEntity $cartEntity): self
    {
        if (!$this->cartEntitys->contains($cartEntity)) {
            $this->cartEntitys[] = $cartEntity;
            $cartEntity->setOrderEntity($this);
        }

        return $this;
    }

    public function removeCartEntity(CartEntity $cartEntity): self
    {
        if ($this->cartEntitys->contains($cartEntity)) {
            $this->cartEntitys->removeElement($cartEntity);
            // set the owning side to null (unless already changed)
            if ($cartEntity->getOrderEntity() === $this) {
                $cartEntity->setOrderEntity(null);
            }
        }

        return $this;
    }

    public function getBuyer(): ?UserEntity
    {
        return $this->buyer;
    }

    public function setBuyer(?UserEntity $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getOrderTotalPrice(): ?float
    {
        return $this->orderTotalPrice;
    }

    public function setOrderTotalPrice(float $orderTotalPrice): self
    {
        $this->orderTotalPrice = $orderTotalPrice;

        return $this;
    }

    public function getFinalPrice(): ?float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(float $finalPrice): self
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    public function getOrderStatus(): ?int
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(int $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getUserAddressEntity(): ?UserAddressEntity
    {
        return $this->userAddressEntity;
    }

    public function setUserAddressEntity(?UserAddressEntity $userAddressEntity): self
    {
        $this->userAddressEntity = $userAddressEntity;

        return $this;
    }

    public function getPayMethod(): ?PayMethodEntity
    {
        return $this->payMethod;
    }

    public function setPayMethod(?PayMethodEntity $payMethod): self
    {
        $this->payMethod = $payMethod;

        return $this;
    }


}
