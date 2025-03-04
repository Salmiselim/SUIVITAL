<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Doctor extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['application/pdf', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger un fichier PDF, JPEG ou PNG valide.'
    )]
    private ?string $proof = null;
    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Ordonnance::class, cascade: ['persist', 'remove'])]
    private Collection $ordonnances;
    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_DOCTOR'];
        $this->isVerified = false;
        $this->ordonnances = new ArrayCollection();
    }

    public function getProof(): ?string
    {
        return $this->proof;
    }

    public function setProof(?string $proof): self
    {
        $this->proof = $proof;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }
    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): self
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances[] = $ordonnance;
            $ordonnance->setDoctor($this);
        }
        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            if ($ordonnance->getDoctor() === $this) {
                $ordonnance->setDoctor(null);
            }
        }
        return $this;
    }
}
