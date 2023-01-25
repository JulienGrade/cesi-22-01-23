<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Blog::class);
        $this->manager = $manager;
    }

    public function save(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Permet de compte le nombre de blogs
     * @return void
     */
    public function getTotalBlogs()
    {
        $query = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)');
        try {
            return $query->getQuery()->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
        }
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getMonthlyBlogs($month){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, $month,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,$month,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(b)
            FROM App\Entity\Blog b
            WHERE b.createdAt <= \'' . $dateEnd .'\' AND b.createdAt >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }



//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blog
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
