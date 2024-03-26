<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateProjection = null;

    #[ORM\Column]
    private ?float $tarifNormal = null;

    #[ORM\Column]
    private ?float $tarifReduit = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'seances')]
    private ?Film $film = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'Seance')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getReservations(): Collection {
        return $this->reservations;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateProjection(): ?\DateTimeInterface
    {
        return $this->dateProjection;
    }

    public function setDateProjection(\DateTimeInterface $dateProjection): static
    {
        $this->dateProjection = $dateProjection;

        return $this;
    }

    public function getTarifNormal(): ?float
    {
        return $this->tarifNormal;
    }

    public function setTarifNormal(float $tarifNormal): static
    {
        $this->tarifNormal = $tarifNormal;

        return $this;
    }

    public function getTarifReduit(): ?float
    {
        return $this->tarifReduit;
    }

    public function setTarifReduit(float $tarifReduit): static
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function getFilm(): ?Film {
        return $this->film;
    }

    public function setFilm(?Film $film): void {
        $this->film = $film;
    }
}
