<?php

namespace App\Entity;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentLike", mappedBy="comment",cascade={"remove"})
     */
    private $comlikes;

    public function __construct()
    {
        $this->comlikes = new ArrayCollection();
    }

    



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CommentLike[]
     */
    public function getComlikes(): Collection
    {
        return $this->comlikes;
    }

    public function addComlike(CommentLike $comlike): self
    {
        if (!$this->comlikes->contains($comlike)) {
            $this->comlikes[] = $comlike;
            $comlike->setComment($this);
        }

        return $this;
    }

    public function removeComlike(CommentLike $comlike): self
    {
        if ($this->comlikes->contains($comlike)) {
            $this->comlikes->removeElement($comlike);
            // set the owning side to null (unless already changed)
            if ($comlike->getComment() === $this) {
                $comlike->setComment(null);
            }
        }

        return $this;
    }

  
 public function isliked(User $user):bool{
      foreach ($this->comlikes as $like ) {
          if($like->getUser()==$user) return true;
          # code...
      }
      return false;

 }
   
}
