<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Blog;
use App\Entity\User;
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
        $blogCount = $entityManager->getRepository(Blog::class)->getTotalBlogs();
        $articleCount = $entityManager->getRepository(Articles::class)->getTotalArticles();
        $userCount = $entityManager->getRepository(User::class)->getTotalUsers();
        $lastMonthUser = $entityManager->getRepository(User::class)->getLastMonthUsers();

        return $this->render('Admin/dashboard/index.html.twig', [
            'blog_count' => $blogCount,
            'article_count' => $articleCount,
            'user_count' => $userCount,
            'last_month_users' => $lastMonthUser,
            'current_menu' => 'blog'
        ]);
    }
}
