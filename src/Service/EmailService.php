<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewReclamationNotification()
    {
       /* try {*/
            // Create the email
            $email = (new Email())
                ->from('mservTal@outlook.com')
                ->to('adreceivetal@outlook.com')
                ->subject('Vous avez reçu une nouvelle réclamation !')
                ->text('Une nouvelle réclamation a été soumise dans le système. Veuillez consulter le tableau de bord des réclamations.');
    
           // Send the email
            $this->mailer->send($email);
    
            /*  Debugging confirmation
            die('✅ Email sent successfully!');
    
        } catch (\Exception $e) {
            die('❌ Email sending failed: ' . $e->getMessage());
        } 
    }  */
    
    }
}
