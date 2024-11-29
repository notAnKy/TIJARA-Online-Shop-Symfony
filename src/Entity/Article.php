<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $libArt = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $prixArt = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $catArt = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null; // New property for the image URL

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibArt(): ?string
    {
        return $this->libArt;
    }

    public function setLibArt(?string $libArt): self
    {
        $this->libArt = $libArt;

        return $this;
    }

    public function getPrixArt(): ?float
    {
        return $this->prixArt;
    }

    public function setPrixArt(?float $prixArt): self
    {
        $this->prixArt = $prixArt;

        return $this;
    }

    public function getCatArt(): ?string
    {
        return $this->catArt;
    }

    public function setCatArt(?string $catArt): self
    {
        $this->catArt = $catArt;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
