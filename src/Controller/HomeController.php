<?php
<<<<<<< HEAD

=======
>>>>>>> bb9e0a621a23b9b2737377207fb1c79dfd141d1f
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
<<<<<<< HEAD
    #[Route('/', name: 'app_home')]
=======
    #[Route('/home', name: 'app_home')]
>>>>>>> bb9e0a621a23b9b2737377207fb1c79dfd141d1f
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
<<<<<<< HEAD
=======
            'template' => 'template1',
>>>>>>> bb9e0a621a23b9b2737377207fb1c79dfd141d1f
        ]);
    }
}
