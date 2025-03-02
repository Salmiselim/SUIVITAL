<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\OrdonnanceRepository;
use App\Repository\MedicamentRepository;
use App\Repository\RendezVousRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

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
            'template' => 'template2',
        ]);
    }

    #[Route('/admin/verify/{id<\d+>}', name: 'app_admin_verify')]
    public function verify(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsVerified(true);
        $entityManager->flush();

        $this->addFlash('success', 'User verified successfully.');
        return $this->redirectToRoute('app_admin_dashboard');
    }

    #[Route('/admin/delete/{id<\d+>}', name: 'app_admin_delete')]
    public function delete(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully.');
        return $this->redirectToRoute('app_admin_dashboard');
    }
}