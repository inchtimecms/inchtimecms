<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemEntityRepository")
 */
class SystemEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siteTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siteSubTitle;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siteEmail;

    /**
     * @ORM\Column(type="text")
     */
    private $siteDescription;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSiteTitle(): ?string
    {
        return $this->siteTitle;
    }

    public function setSiteTitle(string $siteTitle): self
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    public function getSiteSubTitle(): ?string
    {
        return $this->siteSubTitle;
    }

    public function setSiteSubTitle(string $siteSubTitle): self
    {
        $this->siteSubTitle = $siteSubTitle;

        return $this;
    }


    public function getSiteEmail(): ?string
    {
        return $this->siteEmail;
    }

    public function setSiteEmail(string $siteEmail): self
    {
        $this->siteEmail = $siteEmail;

        return $this;
    }

    public function getSiteDescription(): ?string
    {
        return $this->siteDescription;
    }

    public function setSiteDescription(string $siteDescription): self
    {
        $this->siteDescription = $siteDescription;

        return $this;
    }
}
