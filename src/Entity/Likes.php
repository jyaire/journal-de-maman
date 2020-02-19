<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikesRepository")
 */
class Likes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles", inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteurlike;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $datelike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getAuteurlike(): ?User
    {
        return $this->auteurlike;
    }

    public function setAuteurlike(?User $auteurlike): self
    {
        $this->auteurlike = $auteurlike;

        return $this;
    }

    public function getDatelike(): ?\DateTimeInterface
    {
        return $this->datelike;
    }

    public function setDatelike(\DateTimeInterface $datelike): self
    {
        $this->datelike = $datelike;

        return $this;
    }
}
