<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $jour;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Journaux", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $journal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="articles")
     */
    private $auteur;

    public function __construct()
    {
        $this->auteur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getJournal(): ?Journaux
    {
        return $this->journal;
    }

    public function setJournal(?Journaux $journal): self
    {
        $this->journal = $journal;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAuteur(): Collection
    {
        return $this->auteur;
    }

    public function addAuteur(User $auteur): self
    {
        if (!$this->auteur->contains($auteur)) {
            $this->auteur[] = $auteur;
            $auteur->setArticles($this);
        }

        return $this;
    }

    public function removeAuteur(User $auteur): self
    {
        if ($this->auteur->contains($auteur)) {
            $this->auteur->removeElement($auteur);
            // set the owning side to null (unless already changed)
            if ($auteur->getArticles() === $this) {
                $auteur->setArticles(null);
            }
        }

        return $this;
    }
}
