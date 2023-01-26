<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Blog;
use App\Entity\User;
use App\Services\ChartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher la page dashboard de l'administration
     * @param EntityManagerInterface $entityManager
     * @param ChartBuilderInterface $chartBuilder
     * @param ChartServices $chartServices
     * @return Response
     */
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $entityManager, ChartBuilderInterface $chartBuilder, ChartServices $chartServices): Response
    {
        for($i=0; $i<=12; $i++){
            $monthlyUser = $entityManager->getRepository(User::class)->getMonthlyUsers($i+1);
            $monthlyUsers[]=[
                $monthlyUser
            ];
        }
        for($i=0; $i<=12; $i++){
            $monthlyBlog = $entityManager->getRepository(Blog::class)->getMonthlyBlogs($i+1);
            $monthlyBlogs[]=[
                $monthlyBlog
            ];
        }
        for($i=0; $i<=12; $i++){
            $monthlyArticle = $entityManager->getRepository(Articles::class)->getMonthlyArticles($i+1);
            $monthlyArticles[]=[
                $monthlyArticle
            ];
        }
        $monthlyUsers = array_map(static function($monthlyUser) {
            return $monthlyUser[0];
        }, $monthlyUsers);
        $monthlyBlogs = array_map(static function($monthlyBlog) {
            return $monthlyBlog[0];
        }, $monthlyBlogs);
        $monthlyArticles = array_map(static function($monthlyArticle) {
            return $monthlyArticle[0];
        }, $monthlyArticles);

        $label = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $userChart = $chartServices->createChart($label, $monthlyUsers, 'Graphique des utilisateurs', '#ef8354');
        $blogChart = $chartServices->createChart($label, $monthlyBlogs, 'Graphique des blogs', '#7fb353');
        $articleChart = $chartServices->createChart($label, $monthlyArticles, 'Graphique des articles', '#60a5fa');

        $blogCount = $entityManager->getRepository(Blog::class)->getTotalBlogs();
        $articleCount = $entityManager->getRepository(Articles::class)->getTotalArticles();
        $userCount = $entityManager->getRepository(User::class)->getTotalUsers();
        $lastMonthUser = $entityManager->getRepository(User::class)->getLastMonthUsers();

        return $this->render('Admin/dashboard/index.html.twig', [
            'blog_count' => $blogCount,
            'article_count' => $articleCount,
            'user_count' => $userCount,
            'last_month_users' => $lastMonthUser,
            'user_chart' => $userChart,
            'blog_chart' => $blogChart,
            'article_chart' => $articleChart,
            'current_menu' => 'blog'
        ]);
    }
}
