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

    #[ORM\ManyToOne(targetEntity: Oeuvre::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Oeuvre $oeuvre = null;

    #[ORM\ManyToOne(targetEntity: Benevole::class, inversedBy: 'photos')]
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

    public function getOeuvre(): ?Oeuvre
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Oeuvre $oeuvre): static
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
