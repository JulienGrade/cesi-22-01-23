<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * Permet de s'inscrire sur le site
     * @return Response
     * @throws \Exception
     */
    #[Route('/inscription', name: 'register')]
    public function index(UserPasswordHasherInterface $encoder,EntityManagerInterface $entityManager, Request $request): Response
    {
        $notification = null;
        $user = new User();
        $userForm = $this->createForm(RegisterType::class, $user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){
            $user = $userForm->getData();
            $search_email = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                // On encode le mot de passe
                $passwordEncoded = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($passwordEncoded);
                // On enregistre la date de création
                $dateTimeZone = new \DateTimeZone('Europe/Paris');
                $date = new \DateTimeImmutable('now', $dateTimeZone);
                $user->setCreatedAt($date);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success_register', 'Vous êtes inscrit, vous pouvez vous connecter !');
                return $this->redirectToRoute('app_login');
            }else{
                $notification = 'L\'email saisit est déjà enregistré sur le site';
            }
        }
        return $this->render('register/index.html.twig', [
            'registerForm' => $userForm,
            'notification' => $notification
        ]);
    }
}
