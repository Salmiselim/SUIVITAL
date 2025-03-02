<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctor_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('doctor/dashboard.html.twig', [
            'user' => $user,
        ]);
    }
}