<?php

namespace App\Controller;

use App\Entity\Articles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * Permet d'afficher la page d'accueil du site
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $last_article = $entityManager->getRepository(Articles::class)->getLastArticle(1);
        return $this->render('homepage/index.html.twig', [
            'last_article' => $last_article,
            'current_menu' => 'homepage'
        ]);
    }
}
