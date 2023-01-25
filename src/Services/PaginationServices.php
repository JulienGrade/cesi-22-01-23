<?php

namespace App\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PaginationServices
{
    // Service permettant la pagination d'une entité

    /**
     * Le nom de l'entité sur laquelle on veut effectuer une pagination
     *
     * @var string
     */
    private string $entityClass;

    /**
     * Les critères sur la requete
     *
     * @var array
     */
    private array $criteria;

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * @param array $criteria
     * @return PaginationServices
     */
    public function setCriteria(array $criteria): PaginationServices
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * représente le orderby de la requête
     * @var array
     */
    private array $orderBy;

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     * @return PaginationServices
     */
    public function setOrderBy(array $orderBy): PaginationServices
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Le nombre d'enregistrement à récupérer
     *
     * @var int
     */
    private int $limit;

    /**
     * La page sur laquelle on se trouve actuellement
     *
     * @var int
     */
    private int $currentPage = 1;

    /**
     * Le nom de l'entité sur laquelle on veut effectuer une pagination
     *
     * @var string
     */
    private $entityManager;

    /**
     * Le moteur de template Twig qui va permettre de générer le rendu de la pagination
     *
     * @var Environment
     */
    private $twig;

    /**
     * Le nom de la route que l'on veut utiliser pour les boutons de la navigation
     *
     * @var string
     */
    private $route;

    /**
     * Le chemin vers le template qui contient la pagination
     *
     * @var string
     */
    private string $templatePath;

    /**
     * Pagination constructor.
     * @param EntityManagerInterface $entityManager
     * @param $templatePath
     * @param RequestStack $request
     * @param Environment $twig
     */
    public function __construct(EntityManagerInterface $entityManager, $templatePath, RequestStack $request, Environment $twig)
    {
        $this->route            = $request->getCurrentRequest()->attributes->get('_route');
        $this->entityManager    = $entityManager;
        $this->templatePath     = $templatePath;
        $this->twig             = $twig;
    }

    /**
     * Permet de récupérer le nombre de pages qui existent sur une entité particulière
     *
     * Elle se sert de Doctrine pour récupérer le repository qui correspond à l'entité que l'on souhaite
     * paginer (voir la propriété $entityClass) puis elle trouve le nombre total d'enregistrements grâce
     * à la fonction findAll() du repository
     *
     * @throws Exception si la propriété $entityClass n'est pas configurée
     * @throws \Exception
     *
     * @return int
     */
    public function getPages():int
    {

        if(empty($this->entityClass)){
            throw new \RuntimeException("Vous n'avez pas spécifié l'entité sur laquelle vous souhaitez
            paginer. Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }

        // On calcule l'offest
        $offset = $this->currentPage * $this->limit - $this->limit;
        // Connaitre le total des enregistrements de la table
        $repo = $this->entityManager->getRepository($this->entityClass);
        $total = count($repo->findBy($this->criteria, $this->orderBy, 10000, 0));

        // On calcule le nb total de page et ceil fonction php arrondi au dessus en cas de 2,3 par ex
        // Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    /**
     * Permet de récupérer les données paginées pour une entité spécifique
     *
     * Elle se sert de Doctrine afin de récupérer le repository pour l'entité spécifiée
     * puis grâce au repository et à sa fonction findBy() on récupère les données dans une
     * certaine limite et en partant d'un offset
     *
     * @throws Exception si la propriété $entityClass n'est pas définie
     * @throws \Exception
     *
     * @return array
     */
    public function getData(): array
    {

        if(empty($this->entityClass)){
            throw new \RuntimeException("Vous n'avez pas spécifié l'entité sur laquelle vous souhaitez
            paginer. Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }

        // On calcule l'offest
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Demander au repository de trouver les éléments
        $repo = $this->entityManager->getRepository($this->entityClass);
        $data = $repo->findBy($this->criteria, $this->orderBy, $this->limit, $offset);
        // Envoyer les éléments
        return $data;
    }

    /**
     * Permet d'afficher le rendu de la navigation au sein d'un template twig !
     *
     * On se sert ici de notre moteur de rendu afin de compiler le template qui se trouve au chemin
     * de notre propriété $templatePath, en lui passant les variables :
     * - page  => La page actuelle sur laquelle on se trouve
     * - pages => le nombre total de pages qui existent
     * - route => le nom de la route à utiliser pour les liens de navigation
     *
     * Attention : cette fonction ne retourne rien, elle affiche directement le rendu
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    /**
     * Permet de spécifier l'entité sur laquelle on souhaite paginer
     * Par exemple :
     * - App\Entity\Ad::class
     * - App\Entity\Comment::class
     *
     * @param string $entityClass
     * @return self
     */
    public function setEntityClass(string $entityClass): PaginationServices
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Permet de récupérer l'entité sur laquelle on est en train de paginer
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * Permet de spécifier le nombre d'enregistrements que l'on souhaite obtenir !
     *
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): PaginationServices
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Permet de récupérer le nombre d'enregistrements qui seront renvoyés
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Permet de spécifier la page que l'on souhaite afficher
     *
     * @param int $page
     * @return self
     */
    public function setPage(int $page): PaginationServices
    {
        $this->currentPage = $page;
        return $this;
    }

    /**
     * Permet de récupérer la page qui est actuellement affichée
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Permet de changer la route par défaut pour les liens de la navigation
     *
     * @param string $route Le nom de la route à utiliser
     * @return self
     */
    public function setRoute(string $route): PaginationServices
    {
        $this->route = $route;
        return $this;
    }

    /**
     * Permet de récupérer le nom de la route qui sera utilisé sur les liens de la navigation
     *
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * Permet de récupérer le templatePath actuellement utilisé
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }


    /**
     * Permet de choisir un template de pagination
     *
     * @param string $templatePath
     * @return self
     */
    public function setTemplatePath(string $templatePath): PaginationServices
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}