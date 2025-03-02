<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
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