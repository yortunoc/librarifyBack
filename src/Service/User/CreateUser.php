<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUser
{
    private EntityManagerInterface $manager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->manager = $manager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function __invoke(string $email, string $password, string $rol): void
    {
        $user = new User(
            Uuid::uuid4(),
            $email
        );
        $password = $this->userPasswordEncoder->encodePassword(
            $user,
            $password
        );
        $user->setPassword($password);
        $user->setRoles([$rol]);
        $this->manager->persist($user);
        $this->manager->flush();
    }
}