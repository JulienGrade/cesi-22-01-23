<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConceptController extends AbstractController
{
    /**
     * Permet d'afficher la page notre concept
     * @return Response
     */
    #[Route('/notre-concept', name: 'concept')]
    public function index(): Response
    {
        return $this->render('concept/index.html.twig');
    }
}
