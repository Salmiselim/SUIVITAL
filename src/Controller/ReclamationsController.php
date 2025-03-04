<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;  // Import the EmailService class


#[Route('/reclamations')]
final class ReclamationsController extends AbstractController
{
    // User Reclamations
    #[Route(name: 'app_reclamations_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'template' => 'template1',
        ]);
    }

    // Admin Reclamations
    #[Route('/admin', name: 'app_admin_reclamations_index', methods: ['GET'])]
    public function indexAdmin(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('admin/R_index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'template' => 'template2',
        ]);
    }

    // User Create New Reclamation
    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the reclamation
            $entityManager->persist($reclamation);
            $entityManager->flush();

            // Step 4: Trigger email to admin
            $emailService->sendNewReclamationNotification($reclamation);  // Only notifying admin

            // Redirect to the user reclamation index page after submission
            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'template' => 'template1',
        ]);
    }

    // Admin Create New Reclamation
    #[Route('/admin/new', name: 'app_admin_reclamations_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the reclamation
            $entityManager->persist($reclamation);
            $entityManager->flush();

            // Trigger email to admin
            $emailService->sendNewReclamationNotification($reclamation);  // Only notifying admin

            // Redirect to the admin reclamation index page after submission
            return $this->redirectToRoute('app_admin_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/R_new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'template' => 'template2',
        ]);
    }

    // Search Reclamations
   #[Route('/search', name: 'app_reclamations_search', methods: ['GET'])]
#[Route('/admin/search', name: 'app_admin_reclamations_search', methods: ['GET'])]
public function search(ReclamationRepository $reclamationRepository, PaginatorInterface $paginator, Request $request): Response
{
    $searchTerm = $request->query->get('searchTerm', '');
    $page = $request->query->getInt('page', 1);

    $queryBuilder = $reclamationRepository->createQueryBuilder('r')
        ->where('r.objet LIKE :searchTerm')
        ->orWhere('r.commentaire LIKE :searchTerm')
        ->setParameter('searchTerm', '%'.$searchTerm.'%')
        ->orderBy('r.id', 'DESC');

    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $page,
        10
    );

    // Check if the user is an admin
    $isAdmin = $this->isGranted('ROLE_ADMIN');

    return $this->render($isAdmin ? 'admin/R_index.html.twig' : 'reclamations/index.html.twig', [
        'reclamations' => $pagination,
        'searchTerm' => $searchTerm,
        'template' => 'template1',
    ]);
}


    // User Show Reclamation
    #[Route('/{id}', name: 'app_reclamations_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
            'template' => 'template1',
        ]);
    }

    // Admin Show Reclamation
    #[Route('/admin/{id}', name: 'app_admin_reclamations_show', methods: ['GET'])]
    public function showAdmin(Reclamation $reclamation): Response
    {
        return $this->render('admin/R_show.html.twig', [
            'reclamation' => $reclamation,
            'template' => 'template2',
        ]);
    }

    // User Edit Reclamation
    #[Route('/{id}/edit', name: 'app_reclamations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamations/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'template' => 'template1',
        ]);
    }

    // Admin Edit Reclamation
    #[Route('/admin/{id}/edit', name: 'app_admin_reclamations_edit', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/R_edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'template' => 'template2',
        ]);
    }

    // User Delete Reclamation
    #[Route('/{id}', name: 'app_reclamations_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }

    // Admin Delete Reclamation
    #[Route('/admin/{id}', name: 'app_admin_reclamations_delete', methods: ['POST'])]
    public function deleteAdmin(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_reclamations_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
