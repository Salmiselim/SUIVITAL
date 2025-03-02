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
            'template' => 'template1',
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

    #[Route('/{id<\d+>}', name: 'app_patient_show', methods: ['GET'])]
    public function show(int $id, PatientRepository $patientRepository): Response
    {
        $patient = $patientRepository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, PatientRepository $patientRepository, EntityManagerInterface $entityManager): Response
    {
        $patient = $patientRepository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

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

    #[Route('/{id<\d+>}', name: 'app_patient_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, PatientRepository $patientRepository, EntityManagerInterface $entityManager): Response
    {
        $patient = $patientRepository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($patient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_patient_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id<\d+>}/ordonnances', name: 'app_user_ordonnances', methods: ['GET'])]
    public function ordonnances(int $id, PatientRepository $patientRepository, OrdonnanceRepository $ordonnanceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $patient = $patientRepository->find($id);

        if (!$patient) {
            throw $this->createNotFoundException('Patient not found');
        }

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

    #[Route('/ordonnance/{id<\d+>}/download', name: 'ordonnance_download')]
    public function downloadOrdonnance(int $id, OrdonnanceRepository $ordonnanceRepository): Response
    {
        $ordonnance = $ordonnanceRepository->find($id);

        if (!$ordonnance) {
            throw $this->createNotFoundException('Ordonnance not found');
        }

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

    #[Route('/dashboard', name: 'app_patient_dashboard')]
    public function patients(): Response
    {
        // Get the current user
        $user = $this->getUser();

        // Check if the current user is a Patient instance
        if ($user instanceof Patient) {
            // Render the dashboard template with patient data
            return $this->render('patient/dashboard.html.twig', [
                'template' => 'template1',
                'user' => $user, // pass the user to the template
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}    
