<?php

namespace App\Controller;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogsController extends AbstractController
{
    /**
     * Permet d'afficher la page des blogs
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/les-blogs', name: 'blogs')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogs = $entityManager->getRepository(Blog::class)->findAll();
        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogs,
            'current_menu' => 'blog'
        ]);
    }
}
