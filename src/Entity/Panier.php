<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'num_panier', type: 'integer')]
    private ?int $numPanier = null;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(name: 'id_art', referencedColumnName: 'id')]
    private ?Article $article = null;

    #[ORM\ManyToOne(targetEntity: Internaute::class)]
    #[ORM\JoinColumn(name: 'id_int', referencedColumnName: 'id')]
    private ?Internaute $internaute = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $emballage = null;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumPanier(): ?int
    {
        return $this->numPanier;
    }

    public function setNumPanier(?int $numPanier): self
    {
        $this->numPanier = $numPanier;

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

    public function getInternaute(): ?Internaute
    {
        return $this->internaute;
    }

    public function setInternaute(?Internaute $internaute): self
    {
        $this->internaute = $internaute;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getEmballage(): ?string
    {
        return $this->emballage;
    }

    public function setEmballage(?string $emballage): self
    {
        $this->emballage = $emballage;

        return $this;
    }
}
