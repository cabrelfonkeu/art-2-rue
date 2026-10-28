<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $datecreation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $datedestruction = null;

    #[ORM\ManyToOne]
    private ?lieu $lieu = null;

    /**
     * @var Collection<int, artiste>
     */
    #[ORM\ManyToMany(targetEntity: artiste::class, inversedBy: 'oeuvres')]
    private Collection $artistes;

    /**
     * @var Collection<int, Technique>
     */
    #[ORM\ManyToMany(targetEntity: Technique::class, inversedBy: 'oeuvres')]
    private Collection $techniques;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'oeuvres')]
    private Collection $oeuvreRecouverte;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'oeuvreRecouverte')]
    private Collection $oeuvres;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'oeuvre', orphanRemoval: true)]
    private Collection $benevole;

    public function __construct()
    {
        $this->artistes = new ArrayCollection();
        $this->techniques = new ArrayCollection();
        $this->oeuvreRecouverte = new ArrayCollection();
        $this->oeuvres = new ArrayCollection();
        $this->benevole = new ArrayCollection();
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

    public function getDatecreation(): ?\DateTimeImmutable
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeImmutable $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDatedestruction(): ?\DateTimeImmutable
    {
        return $this->datedestruction;
    }

    public function setDatedestruction(?\DateTimeImmutable $datedestruction): static
    {
        $this->datedestruction = $datedestruction;

        return $this;
    }

    public function getLieu(): ?lieu
    {
        return $this->lieu;
    }

    public function setLieu(?lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection<int, artiste>
     */
    public function getArtistes(): Collection
    {
        return $this->artistes;
    }

    public function addArtiste(artiste $artiste): static
    {
        if (!$this->artistes->contains($artiste)) {
            $this->artistes->add($artiste);
        }

        return $this;
    }

    public function removeArtiste(artiste $artiste): static
    {
        $this->artistes->removeElement($artiste);

        return $this;
    }

    /**
     * @return Collection<int, Technique>
     */
    public function getTechniques(): Collection
    {
        return $this->techniques;
    }

    public function addTechnique(Technique $technique): static
    {
        if (!$this->techniques->contains($technique)) {
            $this->techniques->add($technique);
        }

        return $this;
    }

    public function removeTechnique(Technique $technique): static
    {
        $this->techniques->removeElement($technique);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOeuvreRecouverte(): Collection
    {
        return $this->oeuvreRecouverte;
    }

    public function addOeuvreRecouverte(self $oeuvreRecouverte): static
    {
        if (!$this->oeuvreRecouverte->contains($oeuvreRecouverte)) {
            $this->oeuvreRecouverte->add($oeuvreRecouverte);
        }

        return $this;
    }

    public function removeOeuvreRecouverte(self $oeuvreRecouverte): static
    {
        $this->oeuvreRecouverte->removeElement($oeuvreRecouverte);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getOeuvres(): Collection
    {
        return $this->oeuvres;
    }

    public function addOeuvre(self $oeuvre): static
    {
        if (!$this->oeuvres->contains($oeuvre)) {
            $this->oeuvres->add($oeuvre);
            $oeuvre->addOeuvreRecouverte($this);
        }

        return $this;
    }

    public function removeOeuvre(self $oeuvre): static
    {
        if ($this->oeuvres->removeElement($oeuvre)) {
            $oeuvre->removeOeuvreRecouverte($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getBenevole(): Collection
    {
        return $this->benevole;
    }

    public function addBenevole(Photo $benevole): static
    {
        if (!$this->benevole->contains($benevole)) {
            $this->benevole->add($benevole);
            $benevole->setOeuvre($this);
        }

        return $this;
    }

    public function removeBenevole(Photo $benevole): static
    {
        if ($this->benevole->removeElement($benevole)) {
            // set the owning side to null (unless already changed)
            if ($benevole->getOeuvre() === $this) {
                $benevole->setOeuvre(null);
            }
        }

        return $this;
    }
}
