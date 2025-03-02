<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager
    ): Response {
        $isFirstUser = $entityManager->getRepository(Admin::class)->count([]) === 0;
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($isFirstUser) {
                $user = new Admin();
            } else {
                $role = $form->get('role')->getData();
                
                if ($role === 'ROLE_DOCTOR') {
                    $user = new Doctor();
                } elseif ($role === 'ROLE_PATIENT') {
                    $user = new Patient();
                    $user->setRoles(['ROLE_PATIENT']);
                } else {
                    $this->addFlash('error', 'Rôle invalide sélectionné.');
                    return $this->redirectToRoute('app_register');
                }
            }

            $user->setEmail($form->get('email')->getData());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());

            if ($user instanceof Doctor) {
                $proofFile = $form->get('proof')->getData();
                if (!$proofFile) {
                    $this->addFlash('error', 'Les médecins doivent télécharger une preuve.');
                    return $this->redirectToRoute('app_register');
                }
                
                $proofDirectory = $this->getParameter('proof_directory');
                $proofFileName = 'user_' . uniqid() . '.' . $proofFile->guessExtension();
                try {
                    $proofFile->move($proofDirectory, $proofFileName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier.');
                    return $this->redirectToRoute('app_register');
                }
                
                $user->setProof($proofFileName);
                $user->setIsVerified(false);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'template'=>'template1'
        ]);
    }
}
