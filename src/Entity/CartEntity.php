<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartEntityRepository")
 */
class CartEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $choiceSaleProp;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(0)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity", inversedBy="cartEntities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changeAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContentEntity", inversedBy="cartEntities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productContentEntity;

    /**
     * 购物车当前项是否被结算过了，结算过则不显示。
     * @ORM\Column(type="boolean")
     */
    private $boolChecked;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderEntity", inversedBy="cartEntitys")
     */
    private $orderEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoiceSaleProp(): ?string
    {
        return $this->choiceSaleProp;
    }

    public function setChoiceSaleProp(string $choiceSaleProp): self
    {
        $this->choiceSaleProp = $choiceSaleProp;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getChangeAt(): ?\DateTimeInterface
    {
        return $this->changeAt;
    }

    public function setChangeAt(\DateTimeInterface $changeAt): self
    {
        $this->changeAt = $changeAt;

        return $this;
    }

    public function getProductContentEntity(): ?ContentEntity
    {
        return $this->productContentEntity;
    }

    public function setProductContentEntity(?ContentEntity $productContentEntity): self
    {
        $this->productContentEntity = $productContentEntity;

        return $this;
    }

    public function getBoolChecked(): ?bool
    {
        return $this->boolChecked;
    }

    public function setBoolChecked(bool $boolChecked): self
    {
        $this->boolChecked = $boolChecked;

        return $this;
    }

    public function getOrderEntity(): ?OrderEntity
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(?OrderEntity $orderEntity): self
    {
        $this->orderEntity = $orderEntity;

        return $this;
    }
}
