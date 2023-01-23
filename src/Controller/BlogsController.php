<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogsController extends AbstractController
{
    /**
     * Permet d'afficher la page des blogs
     * @return Response
     */
    #[Route('/les-blogs', name: 'blogs')]
    public function index(): Response
    {
        return $this->render('blogs/index.html.twig');
    }
}
