<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Handle role selection
            $role = $form->get('role')->getData();
            $user->setRoles([$role]);

            // Automatically assign ADMIN role to the first user
            $userRepository = $entityManager->getRepository(User::class);
            if ($userRepository->count([]) === 0) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            // Save the user to generate an ID
            $entityManager->persist($user);
            $entityManager->flush();

            // Handle file upload for doctors
            if ($role === 'ROLE_DOCTOR') {
                $proofFile = $form->get('proof')->getData();
                if (!$proofFile) {
                    $this->addFlash('error', 'Les médecins doivent télécharger une preuve.');
                    return $this->redirectToRoute('app_register');
                }

                // Generate a unique filename using the user's ID
                $proofFileName = 'user_' . $user->getId() . '_' . md5(uniqid()) . '.' . $proofFile->guessExtension();

                // Move the file to the upload directory
                $proofFile->move(
                    $this->getParameter('proof_directory'),
                    $proofFileName
                );

                // Save the filename in the user entity
                $user->setProof($proofFileName);
                $user->setIsVerified(false); // Doctors are not verified initially

                // Update the user entity with the proof filename
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                $user->setIsVerified(true); // Patients are verified instantly
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}