<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateprisevue = null;

    #[ORM\Column(length: 255)]
    private ?string $nomfichier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatoeuvre = null;

    #[ORM\ManyToOne(inversedBy: 'benevole')]
    #[ORM\JoinColumn(nullable: false)]
    private ?oeuvre $oeuvre = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Benevole $benevole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateprisevue(): ?\DateTime
    {
        return $this->dateprisevue;
    }

    public function setDateprisevue(\DateTime $dateprisevue): static
    {
        $this->dateprisevue = $dateprisevue;

        return $this;
    }

    public function getNomfichier(): ?string
    {
        return $this->nomfichier;
    }

    public function setNomfichier(string $nomfichier): static
    {
        $this->nomfichier = $nomfichier;

        return $this;
    }

    public function getEtatoeuvre(): ?string
    {
        return $this->etatoeuvre;
    }

    public function setEtatoeuvre(?string $etatoeuvre): static
    {
        $this->etatoeuvre = $etatoeuvre;

        return $this;
    }

    public function getOeuvre(): ?oeuvre
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?oeuvre $oeuvre): static
    {
        $this->oeuvre = $oeuvre;

        return $this;
    }

    public function getBenevole(): ?Benevole
    {
        return $this->benevole;
    }

    public function setBenevole(?Benevole $benevole): static
    {
        $this->benevole = $benevole;

        return $this;
    }
}
