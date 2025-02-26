<?php

namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use App\Entity\Patient;
    use App\Repository\UserRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\MedicamentRepository;
use App\Repository\RendezVousRepository;
    final class AdminController extends AbstractController
    {
    
        #[Route('/dashboard', name: 'admin_dashboard')]
        public function dashboard(UserRepository $userRepo, OrdonnanceRepository $ord, MedicamentRepository $med, RendezVousRepository $rdv): Response
        {
            $userCount = $userRepo->count([]);
            $ord = $ord->count([]);
            $med = $med->count([]);
            $rdv = $rdv->count([]);
        
            return $this->render('admin/dashboard.html.twig', [
                'userCount' => $userCount,
                'ord' => $ord,
                'med' => $med,
                'rdv' => $rdv,
                'template' => 'template2',
            ]);
        }

        
        
    }
