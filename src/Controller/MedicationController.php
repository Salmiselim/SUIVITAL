<?php

namespace App\Controller;

use App\Service\MedicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicationController extends AbstractController
{
    private $medicationService;

    public function __construct(MedicationService $medicationService)
    {
        $this->medicationService = $medicationService;
    }

    #[Route('/medications/search', name: 'medication_search')]
    public function search(Request $request): Response
    {
        $medicationName = $request->query->get('medication');
        $medicationInfo = null;

        if ($medicationName) {
            try {
                $medicationInfo = $this->medicationService->getMedicationInfo($medicationName);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to fetch medication information at this time');
            }
        }

        return $this->render('admin/medication/search.html.twig', [
            'medicationInfo' => $medicationInfo,
            'medicationName' => $medicationName,
            'template' => 'template1',
        ]);
    }
}
