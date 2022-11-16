<?php

namespace App\DataFixtures;

use App\Entity\User as Model;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture
{
    public function __construct(public   UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager)
    {

        $user = new Model();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                12345678
            )
        );
        $manager->persist($user);
        $manager->flush();
        $user = new Model();
        $user->setEmail('moderator@admin.com');
        $user->setRoles(['ROLE_MODERATOR']);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                12345678
            )
        );
        $manager->persist($user);
        $manager->flush();
    }
}
