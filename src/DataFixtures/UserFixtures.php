<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        $users = [
            1 => [
                'email' => 'gaetan.fouillet@gmail.com',
                'roles' => 'ROLE_ADMIN',
                'password' => 'gaetan',
                'username' => 'Gaetan',
                'photo_profil' => 'img/gamer.png',
                'is_activated' => 1
            ],
            2 => [
                'email' => 'gaetan.fouillet@greta-cfa-aquitaine.academy',
                'roles' => 'ROLE_USER',
                'password' => 'greta',
                'username' => 'GaetanGreta',
                'photo_profil' => 'img/default.png',
                'is_activated' => 1
            ],
            3 => [
                'email' => 'thierry@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'thierry',
                'username' => 'Thierry',
                'photo_profil' => 'img/ours.png',
                'is_activated' => 1
            ],
            4 => [
                'email' => 'guillaume@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'guillaume',
                'username' => 'Guillaume',
                'photo_profil' => 'img/chien.png',
                'is_activated' => 1
            ],
            5 => [
                'email' => 'clement@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'clement',
                'username' => 'Clement',
                'photo_profil' => 'img/naruto.png',
                'is_activated' => 1
            ],
            6 => [
                'email' => 'tommy@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'tommy',
                'username' => 'Tommy',
                'photo_profil' => 'img/ours.png',
                'is_activated' => 1
            ],
            7 => [
                'email' => 'franck@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'franck',
                'username' => 'Franck',
                'photo_profil' => 'img/lapin.png',
                'is_activated' => 1
            ],
            8 => [
                'email' => 'estelle@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'estelle',
                'username' => 'Estelle',
                'photo_profil' => 'img/sorciere.png',
                'is_activated' => 1
            ],
            9 => [
                'email' => 'erwan@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'erwan',
                'username' => 'Erwan',
                'photo_profil' => 'img/default.png',
                'is_activated' => 1
            ],
            10 => [
                'email' => 'florent@gmail.com',
                'roles' => 'ROLE_USER',
                'password' => 'florent',
                'username' => 'Florent',
                'photo_profil' => 'img/gamer.png',
                'is_activated' => 1
            ],

        ];

        foreach ($users as $key => $value) {
            $user = new User();
            $user->setEmail($value['email']);
            $user->setRoles(array($value['roles']));

            $password = $this->encoder->encodePassword($user, $value['password']);
            $user->setPassword($password);

            $user->setUsername($value['username']);
            $user->setPhotoProfil($value['photo_profil']);
            $user->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
            $user->setDateUpdate(new \DateTime(date('Y-m-d H:i:s')));

            $user->setIsActivated($value['is_activated']);

            $manager->persist($user);

            //Add Reference
            $this->addReference('user_' . $key, $user);
        }

        $manager->flush();
    }
}
