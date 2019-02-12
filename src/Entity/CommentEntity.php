<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentEntityRepository")
 */
class CommentEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()iok
     * @ORM\Column(type="integer")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentBody;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserEntity", inversedBy="commentEntities")
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CommentTypeEntity", inversedBy="comments")
     */
    private $commentTypeEntity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContentEntity", inversedBy="commentEntities")
     */
    private $contentEntity;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentBody(): ?string
    {
        return $this->commentBody;
    }

    public function setCommentBody(string $commentBody): self
    {
        $this->commentBody = $commentBody;

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

    public function getAuthor(): ?UserEntity
    {
        return $this->author;
    }

    public function setAuthor(?UserEntity $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCommentTypeEntity(): ?CommentTypeEntity
    {
        return $this->commentTypeEntity;
    }

    public function setCommentTypeEntity(?CommentTypeEntity $commentTypeEntity): self
    {
        $this->commentTypeEntity = $commentTypeEntity;

        return $this;
    }

    public function getContentEntity(): ?ContentEntity
    {
        return $this->contentEntity;
    }

    public function setContentEntity(?ContentEntity $contentEntity): self
    {
        $this->contentEntity = $contentEntity;

        return $this;
    }

}
