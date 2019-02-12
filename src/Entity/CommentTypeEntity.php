<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentTypeEntityRepository")
 */
class CommentTypeEntity
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
    private $commentTypeName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentTypeAlias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentEntity", mappedBy="commentTypeEntity")
     */
    private $comments;

    /**
     * 过滤Comment内容中的html标签,不在此Array中的所有标签,通通过滤掉.格式<img>,得带尖括号
     * @ORM\Column(type="text")
     */
    private $commentFilter;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentTypeName(): ?string
    {
        return $this->commentTypeName;
    }

    public function setCommentTypeName(string $commentTypeName): self
    {
        $this->commentTypeName = $commentTypeName;

        return $this;
    }

    public function getCommentTypeAlias(): ?string
    {
        return $this->commentTypeAlias;
    }

    public function setCommentTypeAlias(string $commentTypeAlias): self
    {
        $this->commentTypeAlias = $commentTypeAlias;

        return $this;
    }

    /**
     * @return Collection|CommentEntity[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(CommentEntity $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCommentTypeEntity($this);
        }

        return $this;
    }

    public function removeComment(CommentEntity $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCommentTypeEntity() === $this) {
                $comment->setCommentTypeEntity(null);
            }
        }

        return $this;
    }

    public function getCommentFilter(): ?string
    {
        return $this->commentFilter;
    }

    public function setCommentFilter(string $commentFilter): self
    {
        $this->commentFilter = $commentFilter;

        return $this;
    }



}
