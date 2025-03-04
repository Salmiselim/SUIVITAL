<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\MedicamentRepository;
use App\Repository\RendezVousRepository;

use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

final class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(HttpFoundationRequest $request, UserRepository $userRepo, OrdonnanceRepository $ord, MedicamentRepository $med, RendezVousRepository $rdv): Response
    {
        $search = $request->query->get('search');

        $doctorCount = $userRepo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u INSTANCE OF App\Entity\Doctor')
            ->getQuery()
            ->getSingleScalarResult();

        $patientCount = $userRepo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u INSTANCE OF App\Entity\Patient')
            ->getQuery()
            ->getSingleScalarResult();

        if ($search) {
            $doctorCount = $userRepo->createQueryBuilder('u')
                ->select('count(u.id)')
                ->where('u INSTANCE OF App\Entity\Doctor')
                ->andWhere('u.name LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->getQuery()
                ->getSingleScalarResult();

            $patientCount = $userRepo->createQueryBuilder('u')
                ->select('count(u.id)')
                ->where('u INSTANCE OF App\Entity\Patient')
                ->andWhere('u.name LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $this->render('admin/dashboard.html.twig', [
            'doctorCount' => $doctorCount,
            'patientCount' => $patientCount,
            'ord' => $ord->count([]),
            'med' => $med->count([]),
            'rdv' => $rdv->count([]),
            'template' => 'template2',
        ]);
    }
}
