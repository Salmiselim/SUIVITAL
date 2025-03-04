<?php
// src/Controller/ResetPasswordController.php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Security\PasswordResetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_reset_password_request')]
    public function request(Request $request, UserRepository $userRepository, PasswordResetService $passwordResetService): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            // Check if the email exists
            $user = $userRepository->findOneByEmail($email);

           

            // Send the password reset email
            $passwordResetService->sendResetLink($user);

            $this->addFlash('success', 'If the email address is correct, a password reset link has been sent.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset-password/request.html.twig', [
            'form' => $form->createView(),
            'template' => 'template1',
        ]);
    }
}