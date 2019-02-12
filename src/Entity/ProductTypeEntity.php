<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * 商品类型Entity,引用某一类内容类型,再引用内容类型中的原价,现价字段
 * @ORM\Entity(repositoryClass="App\Repository\ProductTypeEntityRepository")
 */
class ProductTypeEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 商品类型的名称
     * @ORM\Column(type="string")
     */
    private $productTypeName;

    /**
     * 商品类型的描述
     * @ORM\Column(type="string")
     */
    private $productDesc;

    /**
     * 商品类型的机器别名
     * @ORM\Column(type="string")
     */
    private $productAlias;
    /**
     * 引用的内容类型
     * @OneToOne(targetEntity="ContentTypeEntity",inversedBy="productTypeEntity")
     * @JoinColumn(name="ref_content_type_id", referencedColumnName="id")
     */
    private $contentTypeEntity;

    /**
     * 引用的主图字段，可以引用一个到多个主图
     * @ORM\Column(name="ref_field_for_mainpic", type="array")
     */
    private $mainPic = [];

    /**
     * 原价字段: 引用的内容类型中的字段别名
     * @ORM\Column(name="ref_field_for_price", type="string")
     */
    private $priceField;

    /**
     * 折扣(现价)字段: 引用的内容类型中的折扣后的字段别名
     * @ORM\Column(name="ref_field_for_discount", type="string")
     */
    private $discountPriceField;

    /**
     * 上架 下架状态
     * @ORM\Column(name="ref_field_for_sailstatus", type="string")
     */
    private $saleStatus;

    /**
     * 销售属性字段: 引用的内容类型中的字段做为销售属性,可以引用多个字段,字段中的元素做迪卡尔积,
     * @ORM\Column(name="ref_field_for_sale_props", type="json")
     */
    private $salePropField = [];

//    /**
//     * 付款方式字段,微信,支付宝,可以有多种付款方式
//     * @ORM\Column(type="array")
//     */
//    private $payMethods = [];

    /**
     * 发货模板.选择发货模板.
     * @ORM\ManyToOne(targetEntity="ShipFeeTemplateEntity")
     * @ORM\JoinColumn(name="ship_fee_template_id", referencedColumnName="id")
     */
    private $shipFeeTemplateEntity;

    /**
     * 实物还是虚拟呢?
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $boolRealOrVirtual;

    /**
     * 是不是虚要发货呢?
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $boolNeedShip;

    /**
     * 是不是已删除,如果删除了则当前类型下的所有内容deleted 都等于1?
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $deleted;

    public function getId()
    {
        return $this->id;
    }

    public function getProductTypeName(): ?string
    {
        return $this->productTypeName;
    }

    public function setProductTypeName(string $productTypeName): self
    {
        $this->productTypeName = $productTypeName;

        return $this;
    }

    public function getProductDesc(): ?string
    {
        return $this->productDesc;
    }

    public function setProductDesc(string $productDesc): self
    {
        $this->productDesc = $productDesc;

        return $this;
    }

    public function getPriceField(): ?string
    {
        return $this->priceField;
    }

    public function setPriceField(string $priceField): self
    {
        $this->priceField = $priceField;

        return $this;
    }

    public function getDiscountPriceField(): ?string
    {
        return $this->discountPriceField;
    }

    public function setDiscountPriceField(string $discountPriceField): self
    {
        $this->discountPriceField = $discountPriceField;

        return $this;
    }

    public function getSalePropField()
    {
        return $this->salePropField;
    }

    public function setSalePropField($salePropField): self
    {
        $this->salePropField = $salePropField;

        return $this;
    }

//    public function getPayMethods(): ?array
//    {
//        return $this->payMethods;
//    }
//
//    public function setPayMethods(array $payMethods): self
//    {
//        $this->payMethods = $payMethods;
//
//        return $this;
//    }

    public function getBoolRealOrVirtual(): ?bool
    {
        return $this->boolRealOrVirtual;
    }

    public function setBoolRealOrVirtual(bool $boolRealOrVirtual): self
    {
        $this->boolRealOrVirtual = $boolRealOrVirtual;

        return $this;
    }

    public function getBoolNeedShip(): ?bool
    {
        return $this->boolNeedShip;
    }

    public function setBoolNeedShip(bool $boolNeedShip): self
    {
        $this->boolNeedShip = $boolNeedShip;

        return $this;
    }

    public function getContentTypeEntity(): ?ContentTypeEntity
    {
        return $this->contentTypeEntity;
    }

    public function setContentTypeEntity(?ContentTypeEntity $contentTypeEntity): self
    {
        $this->contentTypeEntity = $contentTypeEntity;

        return $this;
    }

    public function getShipFeeTemplateEntity(): ?ShipFeeTemplateEntity
    {
        return $this->shipFeeTemplateEntity;
    }

    public function setShipFeeTemplateEntity(?ShipFeeTemplateEntity $shipFeeTemplateEntity): self
    {
        $this->shipFeeTemplateEntity = $shipFeeTemplateEntity;

        return $this;
    }

    public function getProductAlias(): ?string
    {
        return $this->productAlias;
    }

    public function setProductAlias(string $productAlias): self
    {
        $this->productAlias = $productAlias;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getSaleStatus(): ?string
    {
        return $this->saleStatus;
    }

    public function setSaleStatus(string $saleStatus): self
    {
        $this->saleStatus = $saleStatus;

        return $this;
    }

    public function getMainPic(): ?array
    {
        return $this->mainPic;
    }

    public function setMainPic(array $mainPic): self
    {
        $this->mainPic = $mainPic;

        return $this;
    }


}
