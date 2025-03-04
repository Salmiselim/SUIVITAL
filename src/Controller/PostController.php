<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;

#[Route('/post')]
final class PostController extends AbstractController
{
    // Route pour la page d'administration
    #[Route('/admin', name: 'app_post_admin', methods: ['GET'])]
    public function indexAdmin(Request $request, PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        // Nombre de posts par page
        $limit = 3;

        // Page courante
        $page = $request->query->getInt('page', 1);

        // Déterminez l'offset pour la pagination
        $offset = ($page - 1) * $limit;

        // Récupérer les posts avec pagination
        $queryBuilder = $postRepository->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($queryBuilder);
        $totalPosts = count($paginator); // Nombre total de posts pour calculer le nombre de pages

        // Calculer le nombre total de pages
        $totalPages = ceil($totalPosts / $limit);

        // Récupérer les posts
        $posts = $paginator->getIterator();

        // Récupérer les commentaires pour chaque post
        $commentsByPost = [];
        foreach ($posts as $post) {
            $commentsByPost[$post->getId()] = $commentRepository->findBy(['post' => $post], ['created_at' => 'ASC']);
        }

        return $this->render('post/admin_index.html.twig', [
            'posts' => $posts,
            'commentsByPost' => $commentsByPost,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    // Route pour la page d'accueil des posts
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        // Nombre de posts par page
        $limit = 3;

        // Page courante
        $page = $request->query->getInt('page', 1);

        // Déterminez l'offset pour la pagination
        $offset = ($page - 1) * $limit;

        // Récupérer les posts avec pagination
        $queryBuilder = $postRepository->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($queryBuilder);
        $totalPosts = count($paginator); // Nombre total de posts pour calculer le nombre de pages

        // Calculer le nombre total de pages
        $totalPages = ceil($totalPosts / $limit);

        // Récupérer les posts
        $posts = $paginator->getIterator();

        // Récupérer les commentaires pour chaque post
        $commentsByPost = [];
        foreach ($posts as $post) {
            $commentsByPost[$post->getId()] = $commentRepository->findBy(['post' => $post], ['created_at' => 'ASC']);
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'commentsByPost' => $commentsByPost,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    // Route pour la recherche
    #[Route('/search', name: 'post_search', methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('q'); // Récupérer le terme de recherche
        $postsQuery = $postRepository->searchQuery($query); // Appeler la méthode de recherche dans le repository
    
        // Pagination des résultats de recherche
        $pagination = $paginator->paginate(
            $postsQuery, // La requête de recherche retournée par searchQuery()
            $request->query->getInt('page', 1), // Page actuelle
            3 // Nombre de posts par page
        );
    
        // Récupérer les commentaires pour chaque post
        $commentsByPost = [];
        foreach ($pagination as $post) {
            $commentsByPost[$post->getId()] = $commentRepository->findBy(['post' => $post], ['created_at' => 'ASC']);
        }
    
        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
            'query' => $query, // Passe la requête pour l'afficher dans le template
            'currentPage' => $request->query->getInt('page', 1), // Page actuelle
            'totalPages' => ceil(count($pagination) / 10), // Nombre total de pages
            'commentsByPost' => $commentsByPost, // Passe les commentaires associés aux posts
        ]);
    }
    

    // Route pour créer un nouveau post
    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    // Route pour afficher un post spécifique
    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    // Route pour éditer un post
    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    // Route pour supprimer un post
    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}