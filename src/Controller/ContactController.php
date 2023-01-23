<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * Permet d'afficher la page contact
     * @return Response
     */
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
