<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBlogController extends AbstractController
{
    /**
     * Permet d'afficher la page de gestion de blog dans mon espace
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param string $blogIllustrationDir
     * @return Response
     * @throws \Exception
     */
    #[Route('/mon-espace/mes-blogs', name: 'user_blog')]
    public function index(EntityManagerInterface $entityManager, Request $request, string $blogIllustrationDir): Response
    {
        $blogs = $entityManager->getRepository(Blog::class)->findAll();
        $blog = new Blog();
        $blogForm = $this->createForm(BlogType::class, $blog);
        $blogForm->handleRequest($request);
        if($blogForm->isSubmitted() && $blogForm->isValid()){
            // On gère la date de création
            $dateTimeZone = new \DateTimeZone('Europe/Paris');
            $date = new \DateTimeImmutable('now', $dateTimeZone);
            $blog->setCreatedAt($date);
            $blog->setAuthor($this->getUser());

            if($illustration = $blogForm['illustration']->getData()){
                $illustrationFilename = bin2hex(random_bytes(6)).'.'.$illustration->guessExtension();
                try {
                    $illustration->move($blogIllustrationDir, $illustrationFilename);
                } catch (FileException $e){
                    $this->addFlash('error_upload_article_media_new', 'Une erreur est survenue lors de l\'upload de l\'image');
                }
                $blog->setIllustration($illustrationFilename);
            }
            $entityManager->persist($blog);
            $entityManager->flush();


            $this->addFlash(
                'success_blog_create',
                'Votre blog a bien été créé'
            );

            return $this->redirectToRoute('user_blog');
        }

        return $this->render('user_blog/index.html.twig', [
            'blogForm' => $blogForm,
            'current_menu' => 'accueil',
            'current_sidebar' => 'blog'
        ]);
    }
}
