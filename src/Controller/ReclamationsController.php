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

#[Route('/reclamations')]
final class ReclamationsController extends AbstractController
{
    // User Reclamations
    #[Route(name: 'app_reclamations_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    // Admin Reclamations
    #[Route('/admin', name: 'app_admin_reclamations_index', methods: ['GET'])]
    public function indexAdmin(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('admin/R_index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    // User Create New Reclamation
    #[Route('/new', name: 'app_reclamations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamations/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    // Admin Create New Reclamation
    #[Route('/admin/new', name: 'app_admin_reclamations_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/R_new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
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

    // Determine if the request is from the admin panel
    $isAdminRoute = str_contains($request->getPathInfo(), '/admin');

    return $this->render($isAdminRoute ? 'admin/R_index.html.twig' : 'reclamations/index.html.twig', [
        'reclamations' => $pagination,
        'searchTerm' => $searchTerm,
    ]);
}

    // User Show Reclamation
    #[Route('/{id}', name: 'app_reclamations_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    // Admin Show Reclamation
    #[Route('/admin/{id}', name: 'app_admin_reclamations_show', methods: ['GET'])]
    public function showAdmin(Reclamation $reclamation): Response
    {
        return $this->render('admin/R_show.html.twig', [
            'reclamation' => $reclamation,
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
