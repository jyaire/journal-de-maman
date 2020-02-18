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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ajouts")
     */
    private $ajouteur;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $ajoutdate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="modifications")
     */
    private $modifieur;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $modifdate;

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

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getAjouteur(): ?User
    {
        return $this->ajouteur;
    }

    public function setAjouteur(?User $ajouteur): self
    {
        $this->ajouteur = $ajouteur;

        return $this;
    }

    public function getAjoutdate(): ?\DateTimeInterface
    {
        return $this->ajoutdate;
    }

    public function setAjoutdate(?\DateTimeInterface $ajoutdate): self
    {
        $this->ajoutdate = $ajoutdate;

        return $this;
    }

    public function getModifieur(): ?User
    {
        return $this->modifieur;
    }

    public function setModifieur(?User $modifieur): self
    {
        $this->modifieur = $modifieur;

        return $this;
    }

    public function getModifdate(): ?\DateTimeInterface
    {
        return $this->modifdate;
    }

    public function setModifdate(?\DateTimeInterface $modifdate): self
    {
        $this->modifdate = $modifdate;

        return $this;
    }
}
