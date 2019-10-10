<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Aseert ; 
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message="l'email qu vous avais indiquer st deja utlisé" 
 * )
 */
class User implements UserInterface
{
   
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Aseert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Aseert\Length(min=6)
     */
    private $password;


   
  /**
     * @Aseert\Length(min=6)
     * @Aseert\EqualTo(propertyPath="password" ,message="votre titre est short")
     */
    public $confirmpassorwd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article",mappedBy="article")
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="comment")
     */
    private $comment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profil", mappedBy="user", cascade={"persist", "remove"})
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentLike", mappedBy="user")
     */
    private $comelikes;


 

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->comelikes = new ArrayCollection();
        
    } 

  
    public function __toString()
    {
        return $this->username;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function eraseCredentials(){}
    public function getSalt(){}
    public function getRoles(){
        return ['ROLE_USER'];
    }

    public function getarticle()
    {
        return $this->article;
    }

    public function setarticle(?article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $profil === null ? null : $this;
        if ($newUser !== $profil->getUser()) {
            $profil->setUser($newUser);
        }

        return $this;
    }

    /**
     * @return Collection|CommentLike[]
     */
    public function getComelikes(): Collection
    {
        return $this->comelikes;
    }

    public function addComelike(CommentLike $comelike): self
    {
        if (!$this->comelikes->contains($comelike)) {
            $this->comelikes[] = $comelike;
            $comelike->setUser($this);
        }

        return $this;
    }

    public function removeComelike(CommentLike $comelike): self
    {
        if ($this->comelikes->contains($comelike)) {
            $this->comelikes->removeElement($comelike);
            // set the owning side to null (unless already changed)
            if ($comelike->getUser() === $this) {
                $comelike->setUser(null);
            }
        }

        return $this;
    }

    
    

  

   
}
