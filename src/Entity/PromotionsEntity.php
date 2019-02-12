<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 优惠活动entity,
 * @ORM\Entity(repositoryClass="App\Repository\PromotionsEntityRepository")
 */
class PromotionsEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * 优惠活动标题
     * @ORM\Column(type="string")
     */
    private $promotionTitle;

    /**
     * 优惠活动描述
     * @ORM\Column(type="string")
     */
    private $promotionDesc;

    /**
     * 优惠活动类型,1.满件减钱 2.满件打折 3.满金额减钱 4.满金额打折
     * @ORM\Column(type="integer")
     */
    private $promotionType;

    /**
     * 不同类型的优惠活动的具体参数, 以json_array存储
     * @ORM\Column(type="json_array")
     */
    private $promotionValue;

    /**
     * 活动开始时间
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * 活动结束时间
     * @ORM\Column(type="datetime")
     */
    private $endTime;

    /**
     * 优惠活动创建时间
     * @ORM\Column(type="datetime")
     */
    private $createAt;


    public function getId()
    {
        return $this->id;
    }

    public function getPromotionTitle(): ?string
    {
        return $this->promotionTitle;
    }

    public function setPromotionTitle(string $promotionTitle): self
    {
        $this->promotionTitle = $promotionTitle;

        return $this;
    }

    public function getPromotionDesc(): ?string
    {
        return $this->promotionDesc;
    }

    public function setPromotionDesc(string $promotionDesc): self
    {
        $this->promotionDesc = $promotionDesc;

        return $this;
    }

    public function getPromotionType(): ?int
    {
        return $this->promotionType;
    }

    public function setPromotionType(int $promotionType): self
    {
        $this->promotionType = $promotionType;

        return $this;
    }

    public function getPromotionValue()
    {
        return $this->promotionValue;
    }

    public function setPromotionValue($promotionValue): self
    {
        $this->promotionValue = $promotionValue;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

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
}
