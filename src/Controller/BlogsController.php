<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Blog;
use App\Services\PaginationServices;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogsController extends AbstractController
{
    /**
     * Permet d'afficher la page des blogs
     * @param EntityManagerInterface $entityManager
     * @param PaginationServices $pagination
     * @param $page
     * @return Response
     * @throws Exception
     */
    #[Route('/les-blogs/{page<\d+>?1}', name: 'blogs', requirements: ["page" => "\d+"])]
    public function index(EntityManagerInterface $entityManager, PaginationServices $pagination, $page): Response
    {
        $pagination
            ->setOrderBy(['createdAt' => 'DESC'])
            ->setPage($page)
            ->setLimit(1)
            ->setEntityClass(Blog::class)
            ->setCriteria([]);
        $blogs = $pagination->getData();
        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogs,
            'pages' => $pagination->getPages(),
            'page' => $page,
            'pagination' => $pagination,
            'current_menu' => 'blog'
        ]);
    }

    /**
     * Permet d'afficher le détail d'un blog
     * @return Response
     * @throws Exception
     */
    #[Route('/les-blogs/{slug}/{page<\d+>?1}', name: 'blog_show', requirements: ["page" => "\d+"])]
    public function show(EntityManagerInterface $entityManager, $slug, PaginationServices $pagination, $page)
    {
        $blog = $entityManager->getRepository(Blog::class)->findOneBySlug($slug);
        $pagination
            ->setOrderBy(['createdAt' => 'DESC'])
            ->setPage($page)
            ->setLimit(7)
            ->setEntityClass(Articles::class)
            ->setCriteria(['blog' => $blog]);
        $articles = $pagination->getData();

        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
            'articles' => $articles,
            'pages' => $pagination->getPages(),
            'page' => $page,
            'pagination' => $pagination,
            'current_menu' => 'blog'
        ]);
    }
}
