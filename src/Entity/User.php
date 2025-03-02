<?php
<<<<<<< HEAD

=======
// src/Entity/User.php
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
<<<<<<< HEAD

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'role', type: 'string')]
#[ORM\DiscriminatorMap(['doctor' => Doctor::class, 'patient' => Patient::class])]
class User
=======
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

<<<<<<< HEAD
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;


=======
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire. Veuillez entrer une adresse email.")]
    #[Assert\Email(
        message: "L'email '{{ value }}' n'est pas valide. Veuillez entrer une adresse email au format 'exemple@exemple.com'."
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['application/pdf', 'image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger un fichier PDF, JPEG ou PNG valide.'
    )]
    private ?string $proof = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire. Veuillez entrer votre nom.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire. Veuillez entrer votre prénom.")]
    private ?string $prenom = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters and Setters
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

=======
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
    public function getEmail(): ?string
    {
        return $this->email;
    }

<<<<<<< HEAD
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
=======
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
    {
        return $this->password;
    }

<<<<<<< HEAD
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
=======
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
}
>>>>>>> 86f1022adcf42c19b2e0994cccd7ca26cc4a3e5c
