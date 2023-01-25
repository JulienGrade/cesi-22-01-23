<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 *
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Articles::class);
        $this->manager = $manager;
    }

    public function save(Articles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Articles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Permet de rechercher les articles d'un utilisateur
     * @param $user
     * @return float|int|mixed|string
     */
    public function findUserArticles($user): mixed
    {
        return $this->createQueryBuilder('a')
            ->groupBy('a')
            ->andWhere('a.author = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getLastArticle($limit)
    {
        return $this->createQueryBuilder('a')
            ->groupBy('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Permet de rechercher le nombre d'articles
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getTotalArticles(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)');


        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getMonthlyArticles($month){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, $month,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,$month,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(a)
            FROM App\Entity\Articles a
            WHERE a.createdAt <= \'' . $dateEnd .'\' AND a.createdAt >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }


//    /**
//     * @return Articles[] Returns an array of Articles objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Articles
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
