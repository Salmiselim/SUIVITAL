<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/ordonnance')]
final class OrdonnanceController extends AbstractController
{
    #[Route(name: 'app_ordonnance_index', methods: ['GET'])]
    public function index(
        OrdonnanceRepository $ordonnanceRepository, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response {
        $query = $ordonnanceRepository->createQueryBuilder('o')->getQuery();

        $ordonnances = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('ordonnance/index.html.twig', [
            'ordonnances' => $ordonnances,
            'template' => 'template2',
        ]);
    }

    #[Route('/new/{template}', name: 'app_ordonnance_new', methods: ['GET', 'POST'], defaults: ['template' => 'template2'])]
public function new(
    Request $request,
    RequestStack $requestStack,
    EntityManagerInterface $entityManager,
    DoctorRepository $doctorRepository,
    string $template
): Response {
    $ordonnance = new Ordonnance();

    $session = $requestStack->getSession();
    $doctorId = $session->get('doctorId');

    if (!$doctorId) {
        $this->addFlash('error', 'Doctor ID is missing. Please log in again.');
        return $this->redirectToRoute('app_doctor_index');
    }

    $doctor = $doctorRepository->find($doctorId);
    if (!$doctor) {
        $this->addFlash('error', 'Doctor not found.');
        return $this->redirectToRoute('app_doctor_index');
    }

    $ordonnance->setDoctor($doctor);

    $form = $this->createForm(OrdonnanceType::class, $ordonnance);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($ordonnance);
        $entityManager->flush();

        if ($template === 'template1') {
            return $this->redirect("http://127.0.0.1:8000/doctor/home/{$doctorId}");
        } elseif ($template === 'template2') {
            return $this->redirectToRoute('app_ordonnance_index');
        }

        return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('ordonnance/new.html.twig', [
        'ordonnance' => $ordonnance,
        'form' => $form->createView(),
        'template' => $template,
        'doctorId' => $doctorId,
    ]);
}

    
    #[Route('/{id}/{template}', name: 'app_ordonnance_show', methods: ['GET'], defaults: ['template' => 'template1'])]
    public function show(Ordonnance $ordonnance,string $template): Response
    {
        return $this->render('ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance,
            'template' => $template, 

        ]);
    }

    #[Route('/{id}/edit/{template}', name: 'app_ordonnance_edit', methods: ['GET', 'POST'], defaults: ['template' => 'template1'])]
    public function edit(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager ,string $template): Response
    {
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ordonnance/edit.html.twig', [
            'ordonnance' => $ordonnance,
            'form' => $form,
             'template' => $template, 

        ]);
    }

    #[Route('/{id}', name: 'app_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonnance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ordonnance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ordonnance_index', [], Response::HTTP_SEE_OTHER);
        
    }
    
   
}