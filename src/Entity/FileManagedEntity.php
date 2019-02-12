<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileManagerEntityRepository")
 */
class FileManagedEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 保存后生成的uuid文件名
     * @ORM\Column(type="string", length=255)
     */
    private $uuidName;

    /**
     * 文件原名
     * @ORM\Column(type="string", length=255)
     */
    private $fileName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filemime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileSize;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"unsigned":true, "default":0})
     */
    private $deleted;

    /**
     * @ORM\OneToOne(targetEntity="FieldFileTableEntity", mappedBy="fileManagedEntity")
     */
    private $fieldFileTableEntity;

    /**
     * @ORM\OneToOne(targetEntity="FieldImageTableEntity", mappedBy="fileManagedEntity")
     */
    private $fieldImageTableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity", inversedBy="fileManagedEntities")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuidName(): ?string
    {
        return $this->uuidName;
    }

    public function setUuidName(string $uuidName): self
    {
        $this->uuidName = $uuidName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getFilemime(): ?string
    {
        return $this->filemime;
    }

    public function setFilemime(string $filemime): self
    {
        $this->filemime = $filemime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeInterface $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function getFileSize(): ?string
    {
        return $this->fileSize;
    }

    public function setFileSize(string $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    public function setDeleted(?int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getFieldFileTableEntity(): ?FieldFileTableEntity
    {
        return $this->fieldFileTableEntity;
    }

    public function setFieldFileTableEntity(?FieldFileTableEntity $fieldFileTableEntity): self
    {
        $this->fieldFileTableEntity = $fieldFileTableEntity;

        // set (or unset) the owning side of the relation if necessary
        $newFileManagedEntity = $fieldFileTableEntity === null ? null : $this;
        if ($newFileManagedEntity !== $fieldFileTableEntity->getFileManagedEntity()) {
            $fieldFileTableEntity->setFileManagedEntity($newFileManagedEntity);
        }

        return $this;
    }

    public function getFieldImageTableEntity(): ?FieldImageTableEntity
    {
        return $this->fieldImageTableEntity;
    }

    public function setFieldImageTableEntity(?FieldImageTableEntity $fieldImageTableEntity): self
    {
        $this->fieldImageTableEntity = $fieldImageTableEntity;

        // set (or unset) the owning side of the relation if necessary
        $newFileManagedEntity = $fieldImageTableEntity === null ? null : $this;
        if ($newFileManagedEntity !== $fieldImageTableEntity->getFileManagedEntity()) {
            $fieldImageTableEntity->setFileManagedEntity($newFileManagedEntity);
        }

        return $this;
    }

    public function getAuthor(): ?UserEntity
    {
        return $this->author;
    }

    public function setAuthor(?UserEntity $author): self
    {
        $this->author = $author;

        return $this;
    }

}
