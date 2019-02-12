<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 运费模板
 * @ORM\Entity(repositoryClass="App\Repository\ShipFeeTemplateEntityRepository")
 */
class ShipFeeTemplateEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 模板名称
     * @ORM\Column(type="string")
     */
    private $templateName;

    /**
     * 宝贝所在省份
     * @ORM\Column(type="string")
     */
    private $province;
    /**
     * 宝贝所在城市
     * @ORM\Column(type="string")
     */
    private $city;
    /**
     * 宝贝所在县区
     * @ORM\Column(type="string")
     */
    private $district;

    /**
     * 发货时间,
     * @ORM\Column(type="string")
     */
    private $shipTimeAfterOrder;

    /**
     * 是否包邮
     * @ORM\Column(type="boolean",options={"default":true})
     */
    private $shipIsFree;

    /**
     * 发货方式 快递/EMS 和费用, 默认运费 几 件内 几 元,增加 几 件 增加 几 元. 使用json_array存储
     * @ORM\Column(type="json")
     */
    private $shipMethods=[];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $boolDefault;

    public function getId()
    {
        return $this->id;
    }

    public function getTemplateName(): ?string
    {
        return $this->templateName;
    }

    public function setTemplateName(string $templateName): self
    {
        $this->templateName = $templateName;

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

    public function getShipTimeAfterOrder(): ?string
    {
        return $this->shipTimeAfterOrder;
    }

    public function setShipTimeAfterOrder(string $shipTimeAfterOrder): self
    {
        $this->shipTimeAfterOrder = $shipTimeAfterOrder;

        return $this;
    }

    public function getShipIsFree(): ?bool
    {
        return $this->shipIsFree;
    }

    public function setShipIsFree(bool $shipIsFree): self
    {
        $this->shipIsFree = $shipIsFree;

        return $this;
    }

    public function getShipMethods()
    {
        return $this->shipMethods;
    }

    public function setShipMethods($shipMethods): self
    {
        $this->shipMethods = $shipMethods;

        return $this;
    }

    public function getBoolDefault(): ?bool
    {
        return $this->boolDefault;
    }

    public function setBoolDefault(?bool $boolDefault): self
    {
        $this->boolDefault = $boolDefault;

        return $this;
    }
}
