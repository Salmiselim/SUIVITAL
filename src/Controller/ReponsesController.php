<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reponses')]
final class ReponsesController extends AbstractController
{
    // 📌 User Reponses Index
    #[Route(name: 'app_reponses_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponses/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    // 📌 Admin Reponses Index
    #[Route('/admin', name: 'app_admin_reponses_index', methods: ['GET'])]
    public function indexAdmin(ReponseRepository $reponseRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    // 📌 User Create New Reponse
    #[Route('/new', name: 'app_reponses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponses/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    // 📌 Admin Create New Reponse
    #[Route('/admin/new', name: 'app_admin_reponses_new', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    // 📌 User Show Reponse
    #[Route('/{id}', name: 'app_reponses_show', methods: ['GET'])]
    public function showUser(Reponse $reponse): Response
    {
        return $this->render('reponses/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    // 📌 Admin Show Reponse
    #[Route('/admin/{id}', name: 'app_admin_reponses_show', methods: ['GET'])]
    public function showAdmin(Reponse $reponse): Response
    {
        return $this->render('admin/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    // 📌 User Edit Reponse
    #[Route('/{id}/edit', name: 'app_reponses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponses/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    // 📌 Admin Edit Reponse
    #[Route('/admin/{id}/edit', name: 'app_admin_reponses_edit', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reponses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    // 📌 User Delete Reponse
    #[Route('/{id}', name: 'app_reponses_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(),$request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponses_index', [], Response::HTTP_SEE_OTHER);
    }

    // 📌 Admin Delete Reponse
    #[Route('/admin/{id}', name: 'app_admin_reponses_delete', methods: ['POST'])]
    public function deleteAdmin(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_reponses_index', [], Response::HTTP_SEE_OTHER);
    }
}
