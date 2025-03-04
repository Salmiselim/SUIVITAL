<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/doctor')]
final class DoctorController extends AbstractController
{
    #[Route(name: 'app_doctor_index', methods: ['GET'])]
    public function index(DoctorRepository $doctorRepository): Response
    {
        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctorRepository->findAll(),
            'template' => 'template1',
        ]);
    }

    #[Route('/new', name: 'app_doctor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_doctor_show', methods: ['GET'])]
    public function show(int $id, DoctorRepository $doctorRepository): Response
    {
        $doctor = $doctorRepository->find($id);

        if (!$doctor) {
            throw $this->createNotFoundException('Doctor not found');
        }

        return $this->render('doctor/show.html.twig', [
            'doctor' => $doctor,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_doctor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, DoctorRepository $doctorRepository, EntityManagerInterface $entityManager): Response
    {
        $doctor = $doctorRepository->find($id);

        if (!$doctor) {
            throw $this->createNotFoundException('Doctor not found');
        }

        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_doctor_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, DoctorRepository $doctorRepository, EntityManagerInterface $entityManager): Response
    {
        $doctor = $doctorRepository->find($id);

        if (!$doctor) {
            throw $this->createNotFoundException('Doctor not found');
        }

        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_doctor_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/home', name: 'app_doctor_home', methods: ['GET'])]
    #[IsGranted('ROLE_DOCTOR')]
    public function home(DoctorRepository $doctorRepository, RequestStack $requestStack): Response
    {
        $user = $this->getUser();
    
        if (!$user instanceof Doctor) {
            throw $this->createAccessDeniedException('Access denied. Only doctors can access this page.');
        }
    
        $session = $requestStack->getSession();
        $session->set('doctorId', $user->getId());
    
        return $this->render('doctor/home.html.twig', [
            'doctor_name' => $user->getNom(),
            'template' => 'template1',
            'isVerified' => $user->isVerified(), 

        ]);
    }
    

    #[Route('/users', name: 'app_doctor_dashboard')]
    public function userlist(): Response
    {
        $user = $this->getUser();
        return $this->render('doctor/dashboard.html.twig', [
            'user' => $user,
            'template' => 'template1',

        ]);
    }
}