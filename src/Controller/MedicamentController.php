<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/medicament')]
final class MedicamentController extends AbstractController
{
    #[Route(name: 'app_medicament_index', methods: ['GET'])]
    public function index(
        MedicamentRepository $medicamentRepository, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response {
        $search = $request->query->get('search');
    
        $queryBuilder = $medicamentRepository->createQueryBuilder('m');
    
        if ($search) {
            $queryBuilder->andWhere('m.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
    
        $query = $queryBuilder->getQuery();
        $medicaments = $paginator->paginate($query, $request->query->getInt('page', 1), 5);
    
        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medicaments,
            'template' => 'template2',
        ]);
    }
    

    #[Route('/new', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
            'template' => 'template2',

        ]);
    }

    #[Route('/{id}', name: 'app_medicament_show', methods: ['GET'])]
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
            'template' => 'template2',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
            'template' => 'template2',
        ]);
    }

    #[Route('/{id}', name: 'app_medicament_delete', methods: ['POST'])]
    public function delete(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medicament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search-medicament', name: 'search_medicament', methods: ['GET'])]
public function searchMedicament(Request $request, MedicamentRepository $medicamentRepository): JsonResponse
{
    $query = $request->query->get('q');
    $medicaments = $medicamentRepository->createQueryBuilder('m')
        ->where('m.name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();

    $medicamentNames = array_map(fn($medicament) => $medicament->getName(), $medicaments);
    
    return $this->json($medicamentNames);
}

}