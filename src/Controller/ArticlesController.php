<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * Permet d'afficher la page de détail d'un article
     * @param EntityManagerInterface $entityManager
     * @param $slug
     * @return Response
     */
    #[Route('/article/{slug}', name: 'article_show')]
    public function show(EntityManagerInterface $entityManager, $slug): Response
    {
        $article = $entityManager->getRepository(Articles::class)->findOneBySlug($slug);
        return $this->render('articles/index.html.twig', [
            'article' => $article,
            'current_menu' => 'blog',
        ]);
    }
}
