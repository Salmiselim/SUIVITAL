<?php
namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Ordonnance::class, cascade: ['persist', 'remove'])]
    private Collection $ordonnances;

    public function __construct()
    {
        parent::__construct();
        $this->ordonnances = new ArrayCollection();
    }

    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): self
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances[] = $ordonnance;
            $ordonnance->setPatient($this);
        }
        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            if ($ordonnance->getPatient() === $this) {
                $ordonnance->setPatient(null);
            }
        }
        return $this;
    }

    public function getRoles(): array
    {
        return array_unique(array_merge(parent::getRoles(), ['ROLE_PATIENT']));
    }
    
    
}
