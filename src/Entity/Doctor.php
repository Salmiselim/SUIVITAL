<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    public function __construct()
    {
        parent::__construct();
        $this->roles = ['ROLE_DOCTOR'];
        $this->isVerified = false;
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
}
