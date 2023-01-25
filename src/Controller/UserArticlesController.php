<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Images;
use App\Entity\User;
use App\Form\ArticlesType;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserArticlesController extends AbstractController
{
    /**
     * Permet d'afficher la page des articles dans son espace et de créer des articles
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param string $articleImagesDir
     * @return Response
     * @throws Exception
     */
    #[Route('/mon-espace/articles', name: 'user_articles')]
    public function index(EntityManagerInterface $entityManager, Request $request, string $articleImagesDir): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if($user){
            $articles = $entityManager->getRepository(Articles::class)->findUserArticles($user);
        }else{
            return $this->redirectToRoute('app_login');
        }


        $article = new Articles();
        $articleForm = $this->createForm(ArticlesType::class, $article, array('user' => $this->getUser()));
        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid()){
            // On traite les images
            $articlesImages = $articleForm->get('images')->getData();
            foreach ($articlesImages as $articleImage){
                $articleImageFilename = bin2hex(random_bytes(6)).'.'.$articleImage->guessExtension();

                try {
                    $articleImage->move($articleImagesDir, $articleImageFilename);
                } catch (FileException $e){
                    $this->addFlash('error_upload_article_image', 'Une erreur est survenue lors de l\'upload de l\'image');
                }
                $img = new Images();
                $img->setName($articleImageFilename);
                $article->addImage($img);
            }
            // On attribue la date de création du produit automatiquement
            $dateTimeZone = new DateTimeZone('Europe/Paris');
            $date     = new \DateTimeImmutable('now', $dateTimeZone);
            $article->setCreatedAt($date);

            // On enregistre l'auteur
            $article->setAuthor($user);

            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash(
                'article_create_success',
                'Votre article a bien été ajouté !'
            );

            return $this->redirectToRoute('user_articles');
        }

        return $this->render('user_articles/index.html.twig', [
            'articleForm' => $articleForm->createView(),
            'articles' => $articles,
            'current_menu' => 'accueil',
            'current_sidebar' => 'articles'
        ]);
    }
}