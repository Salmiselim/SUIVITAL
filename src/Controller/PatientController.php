<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\OrdonnanceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Ordonnance;  

#[Route('/patient')]
final class PatientController extends AbstractController
{
    #[Route(name: 'app_patient_index', methods: ['GET'])]
    public function index(PatientRepository $patientRepository): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $patientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('patient/new.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_patient_show', methods: ['GET'])]
    public function show(Patient $patient): Response
    {
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_patient_delete', methods: ['POST'])]
    public function delete(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($patient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/ordonnances', name: 'app_user_ordonnances', methods: ['GET'])]
    public function ordonnances(Patient $patient, OrdonnanceRepository $ordonnanceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Fetch Ordonnances WITHOUT pagination first
        $ordonnances = $ordonnanceRepository->findBy(['patient' => $patient]);
    

        // Now apply pagination
        $query = $ordonnanceRepository->createQueryBuilder('o')
            ->where('o.patient = :patient')
            ->setParameter('patient', $patient)
            ->orderBy('o.datePrescription', 'DESC')
            ->getQuery();
    
        $ordonnancesPaginated = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );
    
        return $this->render('user/home.html.twig', [
            'patient' => $patient,
            'ordonnances' => $ordonnancesPaginated,
            'template' => 'template1',
        ]);
    }
    #[Route('/ordonnance/{id}/download', name: 'ordonnance_download')]
    public function downloadOrdonnance(Ordonnance $ordonnance): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
    
        $dompdf = new Dompdf($options);
        $html = $this->renderView('user/pdf.html.twig', [
            'ordonnance' => $ordonnance,
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="ordonnance_'.$ordonnance->getId().'.pdf"',
            ]
        );
    }
    #[Route('/patient', name: 'app_patient_dashboard')]
    public function patients(): Response
    {
        $user = $this->getUser();
        return $this->render('patient/dashboard.html.twig', [
            'user' => $user,
        ]);
    }
        
}    
