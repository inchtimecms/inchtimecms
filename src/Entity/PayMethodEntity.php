<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 付款方式Entity, 前期先做 支付宝,微信,货到付款
 * @ORM\Entity(repositoryClass="App\Repository\PayMethodEntityRepository")
 */
class PayMethodEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * 付款方式名称
     * @ORM\Column(type="string")
     */
    private $payMethodName;

    /**
     * 付款方式描述
     * @ORM\Column(type="string")
     */
    private $payMethodDesc;

    /**
     * 付款方式别名
     * @ORM\Column(type="string")
     */
    private $payMethodAlias;

    public function getId()
    {
        return $this->id;
    }

    public function getPayMethodName(): ?string
    {
        return $this->payMethodName;
    }

    public function setPayMethodName(string $payMethodName): self
    {
        $this->payMethodName = $payMethodName;

        return $this;
    }

    public function getPayMethodDesc(): ?string
    {
        return $this->payMethodDesc;
    }

    public function setPayMethodDesc(string $payMethodDesc): self
    {
        $this->payMethodDesc = $payMethodDesc;

        return $this;
    }

    public function getPayMethodAlias(): ?string
    {
        return $this->payMethodAlias;
    }

    public function setPayMethodAlias(string $payMethodAlias): self
    {
        $this->payMethodAlias = $payMethodAlias;

        return $this;
    }
}
