<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?\DateInterval $duree = null;

    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'Film')]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getSeances(): Collection {
        return $this->seances;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDuree(): ?\DateInterval
    {
        return $this->duree;
    }

    public function setDuree(\DateInterval $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}
