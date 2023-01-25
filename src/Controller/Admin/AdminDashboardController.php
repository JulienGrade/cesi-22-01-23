<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher la page dashboard de l'administration
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogs = $entityManager->getRepository(Blog::class)->getTotalBlogs();
        return $this->render('Admin/dashboard/index.html.twig', [
            'blogs' => $blogs,
            'current_menu' => 'blog'
        ]);
    }
}
