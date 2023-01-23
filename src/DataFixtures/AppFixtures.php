<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $dateTimeZone = new \DateTimeZone('Europe/Paris');
        $date = new \DateTimeImmutable('now', $dateTimeZone);
        $adminUser = new User();
        $adminUser->setEmail('admin@gmail.com')
            ->setPseudo('admin')
            ->setPassword($this->encoder->hashPassword($adminUser, 'admin'))
            ->setCreatedAt($date)
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($adminUser);
        $manager->flush();
    }
}
