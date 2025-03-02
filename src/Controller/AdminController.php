<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\MedicamentRepository;
use App\Repository\RendezVousRepository;

final class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepo, OrdonnanceRepository $ord, MedicamentRepository $med, RendezVousRepository $rdv): Response
    {
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

        return $this->render('admin/dashboard.html.twig', [
            'doctorCount' => $doctorCount,
            'patientCount' => $patientCount,
            'ord' => $ord->count([]),
            'med' => $med->count([]),
            'rdv' => $rdv->count([]),
            'template' => 'template2',
        ]);
    }



    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        // Search functionality
        $search = $request->query->get('search');
        $users = $search ? $userRepository->findBySearchTerm($search) : $userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/verify/{id}', name: 'app_admin_verify')]
    public function verify(User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setIsVerified(true);
        $entityManager->flush();

        $this->addFlash('success', 'User verified successfully.');
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/admin/delete/{id}', name: 'app_admin_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully.');
        return $this->redirectToRoute('app_admin_dashboard');
    }
}
