<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le service doit être au moins  {{ limit }} characteres ',
        maxMessage: 'Le service ne doit pas depasser {{ limit }} characteres',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'La specialité doit être au moins  {{ limit }} characteres ',
        maxMessage: 'La specialité ne doit pas depasser {{ limit }} characteres',
    )]
    private ?string $specialite = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(40, message: "Le tarif doit être supérieur à 40.")]
    private ?int $tarif = null;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'service')]
    private Collection $rendezvouses;

    public function __construct()
    {
        $this->rendezvouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(int $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvous(RendezVous $rendezvous): static
    {
        if (!$this->rendezvouses->contains($rendezvous)) {
            $this->rendezvouses->add($rendezvous);
            $rendezvous->setService($this);
        }

        return $this;
    }

    public function removeRendezvous(RendezVous $rendezvous): static
    {
        if ($this->rendezvouses->removeElement($rendezvous)) {
            // set the owning side to null (unless already changed)
            if ($rendezvous->getService() === $this) {
                $rendezvous->setService(null);
            }
        }

        return $this;
    }
}
