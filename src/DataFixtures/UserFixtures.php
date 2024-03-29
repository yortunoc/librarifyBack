<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            Uuid::uuid4(),
            'yurybuk@buk.cl'
        );
        $password = $this->userPasswordEncoder->encodePassword(
            $user,
            '123'
        );
        $user->setPassword($password);
        $user->setRoles(['']);
        $manager->persist($user);
        $manager->flush();
    }
}