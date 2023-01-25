<?php

namespace App\Repository;

use App\Entity\User;
use DateTimeZone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private EntityManagerInterface $manager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    /**
     * Permet de rechercher le nombre d'utilisateurs
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getTotalUsers(){
        $query = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)');


        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Permet de rechercher le nombre d'utilisateurs
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws Exception
     */
    public function getLastMonthUsers(){
        $dateTimeZone = new DateTimeZone('Europe/Paris');
        $date = new \DateTimeImmutable('now', $dateTimeZone);

        $query = $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.createdAt >= :date')
            ->setParameter('date', $date->modify('-1month'));


        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getMonthlyUsers($month){
        $dateStart = new \DateTime();
        $currentYear=$dateStart->format('Y');
        $dateStart->setDate($currentYear, $month,01);
        $dateStart = $dateStart->format('Y-m-d');

        $dateEnd = new \DateTime();
        $dateEnd->setDate($currentYear,$month,30);
        $dateEnd = $dateEnd->format('Y-m-d');

        return $this->manager->createQuery(
            'SELECT COUNT(u)
            FROM App\Entity\User u
            WHERE u.createdAt <= \'' . $dateEnd .'\' AND u.createdAt >= \''. $dateStart.'\''
        )->getSingleScalarResult();
    }



//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
