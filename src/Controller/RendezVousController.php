<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
    // Index method to list all rendezvous and show the form for creating new ones
    #[Route('/rendezvous', name: 'app_rendezvous_index', methods: ['GET', 'POST'])]
    public function index(Request $request, RendezVousRepository $rendezVousRepository, EntityManagerInterface $entityManager): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new rendezvous
            $entityManager->persist($rendezVous);
            $entityManager->flush();

            // Redirect to the index page after success
            return $this->redirectToRoute('app_rendezvous_index');
        }

        return $this->render('rendez-vous/index.html.twig', [
            'rendezvous' => $rendezVousRepository->findAll(), // Retrieve all rendezvous
            'form' => $form->createView(), // Render the form
            'template' => 'template1'

        ]);
    }


    #[Route('/indexfront', name: 'app_rendezvous_indexfront', methods: ['GET'])]
    public function indexfront(RendezVousRepository $rendezvousRepository): Response
    {
        return $this->render('rendezvous/indexfront.html.twig', [
            'rendezvouses' => $rendezvousRepository->findAll(),
        ]);
    }
    // Show details of a specific rendezvous
    #[Route('/{id}/show', name: 'app_rendezvous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVous): Response
    {
        return $this->render('rendez-vous/show.html.twig', [
            'rendezvous' => $rendezVous,
        ]);
    }

    // Edit a specific rendezvous
    #[Route('/{id}/edit', name: 'app_rendezvous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        // Handle form submission for editing
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the existing rendezvous
            $entityManager->flush();

            // Redirect to the index page after success
            return $this->redirectToRoute('app_rendezvous_index');
        }

        return $this->render('rendez-vous/edit.html.twig', [
            'rendezvous' => $rendezVous,
            'form' => $form->createView(), // Render the form for editing
        ]);
    }

    // Delete a specific rendezvous
    #[Route('/{id}/delete', name: 'app_rendezvous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVous, EntityManagerInterface $entityManager): Response
    {
        // CSRF token validation for security
        if ($this->isCsrfTokenValid('delete' . $rendezVous->getId(), $request->request->get('_token'))) {
            // Remove the rendezvous from the database
            $entityManager->remove($rendezVous);
            $entityManager->flush();
        }

        // Redirect back to the index page after deletion
        return $this->redirectToRoute('app_rendezvous_index');
    }

    // Confirm and view the deletion of a rendezvous
    #[Route('/{id}/confirm-delete', name: 'app_rendezvous_confirm_delete', methods: ['GET'])]
    public function confirmDelete(RendezVous $rendezVous): Response
    {
        return $this->render('rendez-vous/confirm_delete.html.twig', [
            'rendezvous' => $rendezVous, // Show the rendezvous details for confirmation
        ]);
    }
}
