<?php
// src/Security/PasswordResetService.php

namespace App\Security;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail; // Use TemplatedEmail
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class PasswordResetService
{
    private $mailer;
    private $router;
    private $entityManager;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function sendResetLink(User $user): void
    {
        // Generate the reset token
        $token = bin2hex(random_bytes(16));

        // Save token to the database
        $user->setPasswordResetToken($token);
        $this->entityManager->flush();

        // Generate the reset password URL
        $resetPasswordUrl = $this->router->generate('app_reset_password_confirm', [
            'token' => $token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        // Send the reset email using TemplatedEmail
        $email = (new TemplatedEmail())
            ->from('tlilimedamine5@gmail.com')
            ->to($user->getEmail())
            ->subject('Password Reset Request')
            ->htmlTemplate('emails/reset_password.html.twig') // Use the Twig template
            ->context([
                'resetUrl' => $resetPasswordUrl,
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}