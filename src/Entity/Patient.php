<?php
namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    

    public function getRoles(): array
    {
        return array_unique(array_merge(parent::getRoles(), ['ROLE_PATIENT']));
    }
    
    
}
