<?php

namespace App\Controller;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBlogController extends AbstractController
{
    /**
     * Permet d'afficher la page de gestion de blog dans mon espace
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/mon-espace/mes-blogs', name: 'user_blog')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogs = $entityManager->getRepository(Blog::class)->findAll();

        return $this->render('user_blog/index.html.twig', [
            'blogs' => $blogs,
            'current_menu' => 'blog',
        ]);
    }
}
