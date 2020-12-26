<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="auteur")
     */
    private $articles;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isValidated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="ajouteur")
     */
    private $ajouts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="modifieur")
     */
    private $modifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaires", mappedBy="auteurcom")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="auteurlike")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="poster")
     */
    private $documents;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->ajouts = new ArrayCollection();
        $this->modifications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuteur($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuteur() === $this) {
                $article->setAuteur(null);
            }
        }

        return $this;
    }

    public function getIsValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getAjouts(): Collection
    {
        return $this->ajouts;
    }

    public function addAjout(Articles $ajout): self
    {
        if (!$this->ajouts->contains($ajout)) {
            $this->ajouts[] = $ajout;
            $ajout->setAjouteur($this);
        }

        return $this;
    }

    public function removeAjout(Articles $ajout): self
    {
        if ($this->ajouts->contains($ajout)) {
            $this->ajouts->removeElement($ajout);
            // set the owning side to null (unless already changed)
            if ($ajout->getAjouteur() === $this) {
                $ajout->setAjouteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getModifications(): Collection
    {
        return $this->modifications;
    }

    public function addModification(Articles $modification): self
    {
        if (!$this->modifications->contains($modification)) {
            $this->modifications[] = $modification;
            $modification->setModifieur($this);
        }

        return $this;
    }

    public function removeModification(Articles $modification): self
    {
        if ($this->modifications->contains($modification)) {
            $this->modifications->removeElement($modification);
            // set the owning side to null (unless already changed)
            if ($modification->getModifieur() === $this) {
                $modification->setModifieur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAuteurcom($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteurcom() === $this) {
                $commentaire->setAuteurcom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setAuteurlike($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getAuteurlike() === $this) {
                $like->setAuteurlike(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setPoster($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getPoster() === $this) {
                $document->setPoster(null);
            }
        }

        return $this;
    }
}
