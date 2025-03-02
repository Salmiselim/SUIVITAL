<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    #[Route('/patient', name: 'app_patient_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('patient/dashboard.html.twig', [
            'user' => $user,
        ]);
    }
}