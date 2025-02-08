<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'blog_default')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/user/default', name: 'api_default')]
    public function local_page(): Response
    {
        return $this->render('default/index.html.twig');
    }

}
